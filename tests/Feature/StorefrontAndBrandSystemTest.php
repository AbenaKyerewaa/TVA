<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontAndBrandSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_homepage_loads_luxury_storefront_and_brand_identity(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('The Vaughn Atelier');
        $response->assertSee('Haute Joaillerie');
        $response->assertSee('Heirlooms Sculpted');
        $response->assertSee('The Aurelia Solitaire Diamond Ring');
    }

    public function test_catalog_displays_jewelry_categories_and_products(): void
    {
        $response = $this->get(route('catalog'));

        $response->assertStatus(200);
        $response->assertSee('The High Jewelry Vault');
        $response->assertSee('Rings');
        $response->assertSee('Necklaces');
        $response->assertSee('The Aurelia Solitaire Diamond Ring');
    }

    public function test_category_filter_in_catalog_works(): void
    {
        $category = Category::where('slug', 'rings')->firstOrFail();

        $response = $this->get(route('catalog', ['category' => $category->slug]));

        $response->assertStatus(200);
        $response->assertSee('The Aurelia Solitaire Diamond Ring');
    }

    public function test_product_detail_page_loads_specifications_and_reviews(): void
    {
        $product = Product::where('slug', 'aurelia-solitaire-diamond-ring')->firstOrFail();

        $response = $this->get(route('product.show', $product->slug));

        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee($product->materials);
        $response->assertSee('Certificate of Authenticity');
        $response->assertSee('Add To Shopping Bag');
    }
}
