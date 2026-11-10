<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\NewsletterSubscriber;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhasesEighteenToTwentyTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $customer;

    protected Category $category;

    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'director@tvajewelry.com',
            'name' => 'Atelier Director',
        ]);

        $this->customer = User::factory()->create([
            'role' => 'customer',
            'email' => 'client@tvajewelry.com',
            'name' => 'Duchess Eleanor',
        ]);

        $this->category = Category::create([
            'name' => 'Bridal Solitaires',
            'slug' => 'bridal-solitaires',
            'is_active' => true,
        ]);

        $this->product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'The Imperial Solitaire 18K',
            'slug' => 'the-imperial-solitaire-18k',
            'sku' => 'TVA-SOL-099',
            'price' => 8500.00,
            'stock_quantity' => 4,
            'is_active' => true,
            'short_description' => 'Solid 18k yellow gold with certified VVS1 solitaire diamond.',
            'description' => 'A masterwork of timeless fine jewelry architecture.',
            'materials' => '18K Yellow Gold',
            'gemstone' => 'Certified Diamond',
            'weight_grams' => 6.2,
        ]);
    }

    public function test_storefront_renders_security_headers(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    public function test_storefront_renders_global_seo_and_json_ld(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('The Vaughn Atelier', false);
        $response->assertSee('<meta name="robots" content="index, follow">', false);
        $response->assertSee('"@type": "JewelryStore"', false);
    }

    public function test_product_detail_page_renders_rich_open_graph_and_schema(): void
    {
        $response = $this->get(route('product.show', $this->product->slug));

        $response->assertStatus(200);
        $response->assertSee($this->product->name);
        $response->assertSee('<meta property="og:type" content="product">', false);
        $response->assertSee('"@type": "Product"', false);
        $response->assertSee('"sku": "TVA-SOL-099"', false);
        $response->assertSee('Share Atelier Piece:', false);
        $response->assertSee('WhatsApp', false);
        $response->assertSee('Pinterest', false);
        $response->assertSee('X (Twitter)', false);
    }

    public function test_dynamic_sitemap_generates_valid_xml(): void
    {
        $response = $this->get(route('sitemap'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/xml; charset=UTF-8');
        $response->assertSee('<?xml version="1.0" encoding="UTF-8"?>', false);
        $response->assertSee('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">', false);
        $response->assertSee(route('product.show', $this->product->slug));
        $response->assertSee(route('collection.show', $this->category->slug));
    }

    public function test_dynamic_robots_txt_generates_proper_disallows(): void
    {
        $response = $this->get(route('robots'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
        $response->assertSee('User-agent: *');
        $response->assertSee('Disallow: /admin/');
        $response->assertSee('Disallow: /checkout/');
        $response->assertSee('Sitemap: '.route('sitemap'));
    }

    public function test_client_can_subscribe_to_newsletter(): void
    {
        $response = $this->post(route('newsletter.subscribe'), [
            'email' => 'patron@collectors.com',
            'source' => 'footer',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'patron@collectors.com',
            'is_active' => true,
        ]);
    }

    public function test_non_admin_cannot_access_newsletter_subscribers_management(): void
    {
        $response = $this->actingAs($this->customer)->get(route('admin.subscribers.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_view_and_filter_subscribers(): void
    {
        NewsletterSubscriber::create([
            'email' => 'vip1@collector.com',
            'is_active' => true,
            'source' => 'storefront',
            'subscribed_at' => now(),
        ]);

        NewsletterSubscriber::create([
            'email' => 'vip2@collector.com',
            'is_active' => false,
            'source' => 'storefront',
            'subscribed_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.subscribers.index'));
        $response->assertStatus(200);
        $response->assertSee('vip1@collector.com');
        $response->assertSee('vip2@collector.com');

        // Filter active only
        $filterResponse = $this->actingAs($this->admin)->get(route('admin.subscribers.index', ['status' => 'active']));
        $filterResponse->assertStatus(200);
        $filterResponse->assertSee('vip1@collector.com');
        $filterResponse->assertDontSee('vip2@collector.com');
    }

    public function test_admin_can_toggle_and_delete_subscribers(): void
    {
        $subscriber = NewsletterSubscriber::create([
            'email' => 'toggle_me@collector.com',
            'is_active' => true,
            'source' => 'storefront',
            'subscribed_at' => now(),
        ]);

        $toggleResponse = $this->actingAs($this->admin)->post(route('admin.subscribers.toggle', $subscriber->id));
        $toggleResponse->assertRedirect();
        $this->assertDatabaseHas('newsletter_subscribers', [
            'id' => $subscriber->id,
            'is_active' => false,
        ]);

        $deleteResponse = $this->actingAs($this->admin)->delete(route('admin.subscribers.destroy', $subscriber->id));
        $deleteResponse->assertRedirect();
        $this->assertDatabaseMissing('newsletter_subscribers', [
            'id' => $subscriber->id,
        ]);
    }

    public function test_admin_can_export_subscribers_csv(): void
    {
        NewsletterSubscriber::create([
            'email' => 'export_target@collector.com',
            'is_active' => true,
            'source' => 'storefront',
            'subscribed_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.subscribers.export'));
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('attachment; filename="tva_collector_circle_subscribers_', $response->headers->get('Content-Disposition') ?? '');
    }

    public function test_coupon_privilege_code_can_be_applied_to_cart(): void
    {
        Coupon::create([
            'code' => 'PRESTIGE20',
            'type' => 'percentage',
            'value' => 20.00,
            'min_spend' => 1000.00,
            'is_active' => true,
            'times_used' => 0,
        ]);

        // Add product to cart
        $this->post(route('cart.store'), [
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        // Apply coupon
        $couponResponse = $this->post(route('cart.coupon.apply'), [
            'code' => 'PRESTIGE20',
        ]);

        $couponResponse->assertRedirect(route('cart.index'));
        $couponResponse->assertSessionHas('status');
    }
}
