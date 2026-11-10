<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ShippingMethod;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_populates_data_and_relationships(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseHas('users', ['email' => 'admin@tvajewelry.com', 'role' => 'admin']);
        $this->assertDatabaseHas('users', ['email' => 'customer@tvajewelry.com', 'role' => 'customer']);

        $this->assertGreaterThan(0, Category::count());
        $this->assertGreaterThan(0, Product::count());
        $this->assertGreaterThan(0, ShippingMethod::count());

        $product = Product::with(['category', 'images', 'variants', 'attributes'])->first();
        $this->assertNotNull($product);
        $this->assertNotNull($product->category);
        $this->assertTrue($product->images->isNotEmpty());
        $this->assertTrue($product->variants->isNotEmpty());
    }
}
