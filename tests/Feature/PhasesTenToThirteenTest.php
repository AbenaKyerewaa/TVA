<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\ShippingMethod;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhasesTenToThirteenTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;

    protected Category $category;

    protected Product $product;

    protected ShippingMethod $shippingMethod;

    protected Coupon $coupon;

    protected function setUp(): void
    {
        parent::setUp();

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

        $this->coupon = Coupon::create([
            'code' => 'WELCOME10',
            'type' => 'percentage',
            'value' => 10.00,
            'min_spend' => 1000.00,
            'starts_at' => now()->subDay(),
            'expires_at' => now()->addMonth(),
            'is_active' => true,
        ]);
    }

    public function test_guest_can_add_product_to_cart_and_view_it(): void
    {
        $response = $this->followingRedirects()->post(route('cart.store'), [
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);

        $response->assertStatus(200);
        $response->assertSee('The Vendôme 18K Solitaire Ring', false);
        $response->assertSee('10,800.00', false); // 2 x 5400
    }

    public function test_cannot_add_more_quantity_than_available_vault_stock(): void
    {
        $response = $this->post(route('cart.store'), [
            'product_id' => $this->product->id,
            'quantity' => 10, // Stock is only 5
        ]);

        $response->assertSessionHasErrors('cart');
    }

    public function test_cart_item_quantity_can_be_updated_or_removed(): void
    {
        $this->actingAs($this->customer);
        $this->post(route('cart.store'), [
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $cartService = app(CartService::class);
        $item = $cartService->getCart()->items()->first();
        $this->assertNotNull($item);

        // Update quantity to 3
        $updateResponse = $this->patch(route('cart.update', $item->id), [
            'quantity' => 3,
        ]);

        $updateResponse->assertRedirect(route('cart.index'));
        $this->assertEquals(3, $cartService->getItemCount());

        // Update quantity to 0 removes item
        $deleteResponse = $this->patch(route('cart.update', $item->id), [
            'quantity' => 0,
        ]);

        $deleteResponse->assertRedirect(route('cart.index'));
        $this->assertEquals(0, $cartService->getItemCount());
    }

    public function test_valid_coupon_applies_discount_and_can_be_removed(): void
    {
        $this->actingAs($this->customer);
        $this->post(route('cart.store'), [
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $response = $this->post(route('cart.coupon.apply'), [
            'code' => 'WELCOME10',
        ]);

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('status');

        $cartService = app(CartService::class);
        $this->assertEquals(540.00, $cartService->getDiscountAmount()); // 10% of 5400

        // Test invalid coupon
        $invalidResponse = $this->post(route('cart.coupon.apply'), [
            'code' => 'NONEXISTENT99',
        ]);
        $invalidResponse->assertSessionHasErrors('coupon');

        // Test coupon removal
        $removeResponse = $this->delete(route('cart.coupon.remove'));
        $removeResponse->assertRedirect(route('cart.index'));
        $this->assertEquals(0.00, $cartService->getDiscountAmount());
    }

    public function test_guest_session_cart_migrates_to_user_upon_sign_in(): void
    {
        // Add to guest cart
        $this->post(route('cart.store'), [
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        // Login
        $this->actingAs($this->customer);

        // Accessing cart now associates with patron
        $cartService = app(CartService::class);
        $cart = $cartService->getCart();

        $this->assertEquals($this->customer->id, $cart->user_id);
        $this->assertEquals(1, $cart->items()->count());
    }

    public function test_guest_can_view_and_toggle_wishlist_without_login(): void
    {
        $response = $this->get(route('wishlist.index'));
        $response->assertStatus(200);
        $response->assertSee('Your private wishlist is empty', false);

        // Guest can toggle an item into wishlist
        $toggleResponse = $this->post(route('wishlist.toggle'), [
            'product_id' => $this->product->id,
        ]);
        $toggleResponse->assertSessionHas('status');

        $wishlistId = session('wishlist_id');
        $viewResponse = $this->withSession(['wishlist_id' => $wishlistId])->get(route('wishlist.index'));
        $viewResponse->assertStatus(200);
        $viewResponse->assertSee('The Vendôme 18K Solitaire Ring', false);
    }

    public function test_authenticated_user_can_toggle_and_move_wishlist_items(): void
    {
        $this->actingAs($this->customer);

        // Toggle item into wishlist
        $toggleResponse = $this->post(route('wishlist.toggle'), [
            'product_id' => $this->product->id,
        ]);
        $toggleResponse->assertSessionHas('status');

        $wishlistResponse = $this->get(route('wishlist.index'));
        $wishlistResponse->assertStatus(200);
        $wishlistResponse->assertSee('The Vendôme 18K Solitaire Ring', false);

        // Move to cart
        $cartService = app(CartService::class);
        $wishlistItem = $this->customer->wishlist->items()->first();
        $this->assertNotNull($wishlistItem);

        $moveResponse = $this->post(route('wishlist.move_to_cart', $wishlistItem->id));
        $moveResponse->assertRedirect(route('cart.index'));

        // Wishlist is now empty and cart has item
        $this->assertEquals(0, $this->customer->wishlist->fresh()->items()->count());
        $this->assertEquals(1, $cartService->getItemCount());
    }

    public function test_checkout_redirects_if_cart_is_empty(): void
    {
        $response = $this->get(route('checkout.index'));
        $response->assertRedirect(route('cart.index'));
    }

    public function test_checkout_creates_order_deducts_inventory_and_redirects_to_payment(): void
    {
        $this->actingAs($this->customer);
        $this->post(route('cart.store'), [
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);

        $checkoutData = [
            'customer_email' => 'lady.genevieve@tva.test',
            'customer_phone' => '+1 212 555 0199',
            'full_name' => 'Lady Genevieve',
            'address_line_1' => '740 Park Avenue',
            'address_line_2' => 'Penthouse B',
            'city' => 'New York',
            'state' => 'NY',
            'postal_code' => '10021',
            'country' => 'United States',
            'shipping_method_id' => $this->shippingMethod->id,
            'payment_method' => 'card',
            'customer_notes' => 'White-glove private courier with wax-sealed gift note.',
        ];

        $response = $this->post(route('checkout.store'), $checkoutData);

        // Order created
        $order = Order::latest()->first();
        $this->assertNotNull($order);
        $this->assertEquals(10800.00, (float) $order->subtotal);
        $this->assertEquals(150.00, (float) $order->shipping_fee);
        $this->assertEquals(10950.00, (float) $order->total_amount);

        // Stock deducted from 5 to 3
        $this->assertEquals(3, $this->product->fresh()->stock_quantity);

        // Cart cleared
        $cartService = app(CartService::class);
        $this->assertEquals(0, $cartService->getItemCount());

        // Redirects to payment screen
        $response->assertRedirect(route('checkout.payment', [
            'order_number' => $order->order_number,
            'method' => 'card',
        ]));
    }

    public function test_payment_processing_updates_order_status_and_creates_shipment(): void
    {
        // Create pending order
        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'user_id' => $this->customer->id,
            'status' => 'pending',
            'payment_status' => 'pending',
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
        ]);

        $order->items()->create([
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'sku' => $this->product->sku,
            'unit_price' => 5400.00,
            'quantity' => 1,
            'total_price' => 5400.00,
        ]);

        // Show payment screen
        $screenResponse = $this->get(route('checkout.payment', $order->order_number));
        $screenResponse->assertStatus(200);
        $screenResponse->assertSee($order->order_number);
        $screenResponse->assertSee('5,550.00');

        // Process payment
        $paymentResponse = $this->post(route('checkout.payment.process', $order->order_number), [
            'payment_method' => 'card',
            'card_name' => 'Lady Genevieve',
        ]);

        $paymentResponse->assertRedirect(route('order.confirmation', $order->order_number));

        // Assert order updated to paid
        $order->refresh();
        $this->assertEquals('paid', $order->payment_status);
        $this->assertEquals('paid', $order->status);
        $this->assertNotNull($order->paid_at);

        // Assert shipment record created
        $this->assertNotNull($order->shipment);
        $this->assertEquals('preparing', $order->shipment->status);

        // View confirmation screen
        $confirmResponse = $this->get(route('order.confirmation', $order->order_number));
        $confirmResponse->assertStatus(200);
        $confirmResponse->assertSee($order->order_number);
        $confirmResponse->assertSee('Acquisition Confirmed', false);
    }

    public function test_official_pdf_invoice_can_be_downloaded(): void
    {
        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'user_id' => $this->customer->id,
            'status' => 'paid',
            'payment_status' => 'paid',
            'shipping_status' => 'pending',
            'currency' => 'USD',
            'subtotal' => 5400.00,
            'discount_amount' => 0.00,
            'shipping_fee' => 0.00,
            'tax_amount' => 0.00,
            'total_amount' => 5400.00,
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

        $order->items()->create([
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'sku' => $this->product->sku,
            'unit_price' => 5400.00,
            'quantity' => 1,
            'total_price' => 5400.00,
        ]);

        $invoiceResponse = $this->get(route('order.invoice', $order->order_number));
        $invoiceResponse->assertStatus(200);
        $this->assertEquals('application/pdf', $invoiceResponse->headers->get('Content-Type'));
        $this->assertStringContainsString("TVA-Invoice-{$order->order_number}.pdf", $invoiceResponse->headers->get('Content-Disposition'));
    }
}
