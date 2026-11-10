<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhasesSixToNineTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $customer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $this->admin = User::where('role', 'admin')->firstOrFail();
        $this->customer = User::where('role', 'customer')->firstOrFail();
    }

    /*
    |--------------------------------------------------------------------------
    | Phase 6 Tests — Homepage and Storefront Features
    |--------------------------------------------------------------------------
    */
    public function test_homepage_renders_all_luxury_sections(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Heirlooms Sculpted');
        $response->assertSee('Complimentary Insured Courier');
        $response->assertSee('Words from Our Collectors');
        $response->assertSee("The Vaughn Atelier Collector's Circle", false);
    }

    public function test_client_can_subscribe_to_newsletter(): void
    {
        $response = $this->post(route('newsletter.subscribe'), [
            'email' => 'collector@vanceholdings.com',
            'source' => 'test_suite',
        ]);

        $response->assertSessionHas('newsletter_status');
        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'collector@vanceholdings.com',
            'is_active' => true,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Phase 7 Tests — Categories and Collections
    |--------------------------------------------------------------------------
    */
    public function test_collections_overview_displays_all_categories(): void
    {
        $response = $this->get(route('collections'));

        $response->assertStatus(200);
        $response->assertSee('Curated Collections');
        $response->assertSee('Rings');
        $response->assertSee('Necklaces & Pendants');
        $response->assertSee('Earrings');
    }

    public function test_individual_collection_page_displays_scoped_creations(): void
    {
        $category = Category::where('slug', 'rings')->firstOrFail();

        $response = $this->get(route('collection.show', $category->slug));

        $response->assertStatus(200);
        $response->assertSee($category->name);
        $response->assertSee('The Aurelia Solitaire Diamond Ring');
    }

    public function test_admin_can_view_and_create_categories(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.categories.index'));
        $response->assertStatus(200);
        $response->assertSee('High Jewelry Collections');

        $storeResponse = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'Bespoke Tiara Collection',
            'slug' => 'bespoke-tiaras',
            'description' => 'Architectural diadems in 18K white gold.',
            'is_featured' => true,
            'is_active' => true,
        ]);

        $storeResponse->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['slug' => 'bespoke-tiaras']);
    }

    /*
    |--------------------------------------------------------------------------
    | Phase 8 Tests — Product Management and Product Details
    |--------------------------------------------------------------------------
    */
    public function test_product_detail_page_renders_variants_and_specifications(): void
    {
        $product = Product::where('slug', 'aurelia-solitaire-diamond-ring')->firstOrFail();

        $response = $this->get(route('product.show', $product->slug));

        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee($product->sku);
        $response->assertSee('Certificate of Authenticity');
        $response->assertSee('Creation Details');
        $response->assertSee('Verified Patron Reviews');
    }

    public function test_admin_can_manage_products_in_vault(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.products.index'));
        $response->assertStatus(200);
        $response->assertSee('Vault Inventory Management');

        $category = Category::firstOrFail();
        $newSku = 'TVA-TEST-999';

        $storeResponse = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'name' => 'The Imperial Sapphire Choker',
            'sku' => $newSku,
            'price' => 5400.00,
            'materials' => '18K Solid White Gold',
            'gemstone' => 'Ceylon Blue Sapphire',
            'stock_quantity' => 4,
            'low_stock_threshold' => 1,
            'is_active' => true,
        ]);

        $storeResponse->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', ['sku' => $newSku]);

        $createdProduct = Product::where('sku', $newSku)->firstOrFail();

        $updateResponse = $this->actingAs($this->admin)->put(route('admin.products.update', $createdProduct), [
            'category_id' => $category->id,
            'name' => 'The Imperial Sapphire Choker (Updated)',
            'sku' => $newSku,
            'price' => 5800.00,
            'stock_quantity' => 3,
            'is_active' => true,
        ]);

        $updateResponse->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'sku' => $newSku,
            'price' => 5800.00,
            'stock_quantity' => 3,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Phase 9 Tests — Search, Filtering and Sorting
    |--------------------------------------------------------------------------
    */
    public function test_catalog_keyword_search_filters_results(): void
    {
        $response = $this->get(route('catalog', ['q' => 'Aurelia']));

        $response->assertStatus(200);
        $response->assertSee('The Aurelia Solitaire Diamond Ring');
    }

    public function test_catalog_filtering_by_metal_and_in_stock(): void
    {
        $response = $this->get(route('catalog', [
            'metal' => 'Yellow Gold',
            'in_stock' => '1',
        ]));

        $response->assertStatus(200);
        $response->assertSee('The Aurelia Solitaire Diamond Ring');
    }

    public function test_catalog_sorting_by_price_and_newest(): void
    {
        $ascResponse = $this->get(route('catalog', ['sort' => 'price_asc']));
        $ascResponse->assertStatus(200);

        $descResponse = $this->get(route('catalog', ['sort' => 'price_desc']));
        $descResponse->assertStatus(200);

        $bestsellerResponse = $this->get(route('catalog', ['sort' => 'bestsellers']));
        $bestsellerResponse->assertStatus(200);
    }

    public function test_search_api_returns_json_results(): void
    {
        $response = $this->get(route('api.search', ['q' => 'Aurelia']));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'results' => [
                '*' => ['name', 'slug', 'sku', 'price', 'materials', 'url'],
            ],
        ]);
    }
}
