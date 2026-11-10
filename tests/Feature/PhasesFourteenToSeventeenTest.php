<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\InventoryLog;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\Shipment;
use App\Models\ShippingMethod;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhasesFourteenToSeventeenTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $customer;

    protected Category $category;

    protected Product $product;

    protected ShippingMethod $shippingMethod;

    protected Order $order;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@tvajewelry.com',
            'name' => 'Atelier Director',
        ]);

        $this->customer = User::factory()->create([
            'role' => 'customer',
            'email' => 'patron@tvajewelry.com',
            'name' => 'Lady Genevieve',
        ]);

        $this->category = Category::create([
            'name' => 'High Jewelry Rings',
            'slug' => 'high-jewelry-rings',
            'is_active' => true,
        ]);

        $this->product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'The Vendôme 18K Solitaire Ring',
            'slug' => 'vendome-18k-solitaire-ring',
            'sku' => 'TVA-RNG-001',
            'price' => 5400.00,
            'compare_at_price' => 6000.00,
            'stock_quantity' => 5,
            'is_active' => true,
            'is_featured' => true,
        ]);

        $this->shippingMethod = ShippingMethod::create([
            'name' => 'TVA Armored Courier',
            'code' => 'TVA_ARMORED',
            'fee' => 150.00,
            'estimated_days' => '1-2 Days',
            'is_active' => true,
        ]);

        $this->order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'user_id' => $this->customer->id,
            'status' => 'paid',
            'payment_status' => 'paid',
            'shipping_status' => 'pending',
            'currency' => 'USD',
            'subtotal' => 5400.00,
            'discount_amount' => 0.00,
            'shipping_fee' => 150.00,
            'tax_amount' => 0.00,
            'total_amount' => 5550.00,
            'shipping_method_id' => $this->shippingMethod->id,
            'shipping_address' => [
                'full_name' => 'Lady Genevieve',
                'address_line_1' => '740 Park Avenue',
                'city' => 'New York',
                'state' => 'NY',
                'postal_code' => '10021',
                'country' => 'United States',
            ],
            'customer_email' => 'patron@tvajewelry.com',
            'customer_phone' => '+1 212 555 0199',
            'paid_at' => now(),
        ]);

        $this->order->items()->create([
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'sku' => $this->product->sku,
            'unit_price' => 5400.00,
            'quantity' => 1,
            'total_price' => 5400.00,
        ]);
    }

    public function test_admin_can_view_orders_and_order_dossier(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.orders.index'));
        $response->assertStatus(200);
        $response->assertSee($this->order->order_number);
        $response->assertSee('Lady Genevieve', false);

        $dossierResponse = $this->get(route('admin.orders.show', $this->order->id));
        $dossierResponse->assertStatus(200);
        $dossierResponse->assertSee($this->order->order_number);
        $dossierResponse->assertSee('The Vendôme 18K Solitaire Ring', false);
    }

    public function test_admin_can_transition_order_status_and_it_manages_shipments(): void
    {
        $this->actingAs($this->admin);

        // Mark as shipped -> creates or updates shipment
        $response = $this->patch(route('admin.orders.status', $this->order->id), [
            'status' => 'shipped',
            'admin_note' => 'Dispatched with armed guard',
        ]);

        $response->assertSessionHas('status');
        $this->order->refresh();
        $this->assertEquals('shipped', $this->order->status);
        $this->assertNotNull($this->order->shipped_at);
        $this->assertNotNull($this->order->shipment);
        $this->assertEquals('in_transit', $this->order->shipment->status);
    }

    public function test_order_cancellation_restores_vault_stock_and_logs_audit(): void
    {
        $this->actingAs($this->admin);
        $initialStock = $this->product->stock_quantity; // 5

        $response = $this->patch(route('admin.orders.status', $this->order->id), [
            'status' => 'cancelled',
            'admin_note' => 'Client requested cancellation',
        ]);

        $response->assertSessionHas('status');
        $this->order->refresh();
        $this->assertEquals('cancelled', $this->order->status);

        // Stock restored from 5 to 6
        $this->assertEquals($initialStock + 1, $this->product->fresh()->stock_quantity);

        // Inventory log created
        $log = InventoryLog::where('reference_id', $this->order->id)
            ->where('change_type', 'return')
            ->first();
        $this->assertNotNull($log);
        $this->assertEquals(1, $log->quantity);
    }

    public function test_admin_can_view_inventory_and_make_manual_stock_adjustments(): void
    {
        $this->actingAs($this->admin);

        $indexResponse = $this->get(route('admin.inventory.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('The Vendôme 18K Solitaire Ring', false);

        // Adjust stock by adding 3 pieces
        $adjustResponse = $this->post(route('admin.inventory.adjust', $this->product->id), [
            'adjustment_type' => 'add',
            'quantity' => 3,
            'notes' => 'Received fresh cast from master jeweler',
        ]);

        $adjustResponse->assertSessionHas('status');
        $this->assertEquals(8, $this->product->fresh()->stock_quantity);

        // Check audit log
        $logsResponse = $this->get(route('admin.inventory.logs'));
        $logsResponse->assertStatus(200);
        $logsResponse->assertSee('Received fresh cast from master jeweler', false);
    }

    public function test_admin_can_manage_shipping_methods(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('admin.shipping-methods.store'), [
            'name' => 'Ferrari Security Armored Flight',
            'code' => 'FERRARI_SECURITY',
            'fee' => 350.00,
            'estimated_days' => 'Next Day Air',
            'description' => 'White-glove international air transport with armed courier escort',
            'is_active' => true,
        ]);

        $response->assertRedirect(route('admin.shipping-methods.index'));
        $this->assertDatabaseHas('shipping_methods', [
            'code' => 'FERRARI_SECURITY',
            'fee' => 350.00,
        ]);

        $method = ShippingMethod::where('code', 'FERRARI_SECURITY')->first();

        // Toggle status
        $toggleResponse = $this->post(route('admin.shipping-methods.toggle', $method->id));
        $toggleResponse->assertSessionHas('status');
        $this->assertFalse($method->fresh()->is_active);
    }

    public function test_admin_can_update_shipment_tracking_and_public_tracking_displays_it(): void
    {
        $shipment = Shipment::create([
            'order_id' => $this->order->id,
            'shipping_method_id' => $this->shippingMethod->id,
            'tracking_number' => 'TVA-SEC-88229911',
            'carrier' => 'TVA Armored Courier',
            'status' => 'preparing',
        ]);

        $this->actingAs($this->admin);

        $updateResponse = $this->patch(route('admin.shipments.update', $shipment->id), [
            'carrier' => 'Ferrari Armored Security',
            'tracking_number' => 'TVA-SEC-88229911',
            'status' => 'in_transit',
            'notes' => 'En route to Park Avenue Residence',
        ]);

        $updateResponse->assertSessionHas('status');
        $this->assertEquals('in_transit', $shipment->fresh()->status);
        $this->assertEquals('Ferrari Armored Security', $shipment->fresh()->carrier);

        // Public tracking by tracking number
        $this->app['auth']->logout();
        $trackingResponse = $this->get(route('order.tracking', ['tracking' => 'TVA-SEC-88229911']));
        $trackingResponse->assertStatus(200);
        $trackingResponse->assertSee('TVA-SEC-88229911', false);
        $trackingResponse->assertSee('Ferrari Armored Security', false);
        $trackingResponse->assertSee('The Vendôme 18K Solitaire Ring', false);
    }

    public function test_customer_dashboard_and_order_dossier_show_acquisitions(): void
    {
        $this->actingAs($this->customer);

        $dashResponse = $this->get(route('dashboard'));
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee($this->order->order_number);
        $dashResponse->assertSee('Acquisition Count', false);

        $ordersIndexResponse = $this->get(route('account.orders.index'));
        $ordersIndexResponse->assertStatus(200);
        $ordersIndexResponse->assertSee($this->order->order_number);

        $orderShowResponse = $this->get(route('account.orders.show', $this->order->order_number));
        $orderShowResponse->assertStatus(200);
        $orderShowResponse->assertSee($this->order->order_number);
        $orderShowResponse->assertSee('The Vendôme 18K Solitaire Ring', false);
    }

    public function test_patron_can_submit_verified_review_for_purchased_piece(): void
    {
        $this->actingAs($this->customer);

        $response = $this->post(route('reviews.store'), [
            'product_id' => $this->product->id,
            'rating' => 5,
            'title' => 'An Architectural Masterpiece',
            'comment' => 'The diamond cut is flawless, with scintillation and pavilion balance unlike anything I have seen.',
        ]);

        $response->assertSessionHas('status');

        $review = Review::where('product_id', $this->product->id)->first();
        $this->assertNotNull($review);
        $this->assertEquals(5, $review->rating);
        $this->assertTrue($review->is_verified_purchase);
        $this->assertTrue($review->is_approved);

        // Duplicate review by same customer is blocked
        $dupResponse = $this->post(route('reviews.store'), [
            'product_id' => $this->product->id,
            'rating' => 4,
            'title' => 'Another review',
            'comment' => 'Second comment',
        ]);
        $dupResponse->assertSessionHasErrors('review');
    }

    public function test_executive_atelier_dashboard_loads_kpis_and_metrics(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Executive Atelier Dashboard', false);
        $response->assertSee('Net Gross Revenue', false);
        $response->assertSee(number_format($this->order->total_amount, 2), false);
    }

    public function test_admin_can_view_customers_directory_and_client_dossier(): void
    {
        $this->actingAs($this->admin);

        $indexResponse = $this->get(route('admin.customers.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Lady Genevieve', false);
        $indexResponse->assertSee('patron@tvajewelry.com', false);

        $showResponse = $this->get(route('admin.customers.show', $this->customer->id));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Lady Genevieve', false);
        $showResponse->assertSee($this->order->order_number);
    }

    public function test_admin_can_manage_promotional_privilege_coupons(): void
    {
        $this->actingAs($this->admin);

        $storeResponse = $this->post(route('admin.coupons.store'), [
            'code' => 'ROYAL25',
            'type' => 'percentage',
            'value' => 25.00,
            'min_spend' => 2000.00,
            'usage_limit' => 50,
            'is_active' => true,
        ]);

        $storeResponse->assertRedirect(route('admin.coupons.index'));
        $this->assertDatabaseHas('coupons', ['code' => 'ROYAL25']);

        $coupon = Coupon::where('code', 'ROYAL25')->first();

        // Toggle
        $toggleResponse = $this->post(route('admin.coupons.toggle', $coupon->id));
        $toggleResponse->assertSessionHas('status');
        $this->assertFalse($coupon->fresh()->is_active);
    }
}
