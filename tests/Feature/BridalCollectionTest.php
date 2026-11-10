<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BridalCollectionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_bridal_page_renders_successfully(): void
    {
        $response = $this->get('/bridal');
        $response->assertStatus(302);
        $response->assertRedirect(route('catalog', ['category' => 'engagement']));

        $catalogResponse = $this->get(route('catalog', ['category' => 'engagement']));
        $catalogResponse->assertStatus(200);
        $catalogResponse->assertSee('Haute Couture Bridal & Suiting', false);
    }

    public function test_all_four_ladies_bridal_gowns_are_present(): void
    {
        $response = $this->get(route('catalog', ['category' => 'engagement', 'segment' => 'gowns']));
        $response->assertStatus(200);

        $response->assertSee('The Aurelia Silk-Mikado Gown & Diamond Choker Suite');
        $response->assertSee('The Vendôme Chantilly Lace Gown & Muzo Emerald Tiara');
        $response->assertSee('The Seraphina Crepe Column Gown & Waterfall Diamond Back-Lariat');
        $response->assertSee('The Celestia Pleated Tulle Gown & Kashmir Sapphire Suite');
    }

    public function test_all_five_mens_bridal_suits_are_present(): void
    {
        $response = $this->get(route('catalog', ['category' => 'engagement', 'segment' => 'suits']));
        $response->assertStatus(200);

        $response->assertSee('The Sovereign Midnight Barathea Tuxedo & Paraíba Tourmaline Brooch');
        $response->assertSee('The Grand Ducal Ivory Jacquard Dinner Jacket & Muzo Emerald Studs');
        $response->assertSee('The Vendôme Charcoal Wool-Silk Three-Piece Suit & Diamond Albert Chain');
        $response->assertSee('The Royal Imperial Black Velvet Tuxedo & Kashmir Sapphire Cufflinks');
        $response->assertSee("The Archduke Champagne Cashmere Groom's Suit & Yellow Diamond Pin");
    }

    public function test_can_view_bridal_product_details_and_add_to_cart(): void
    {
        $product = Product::where('slug', 'aurelia-silk-mikado-gown-diamond-choker')->firstOrFail();

        $detailResponse = $this->get(route('product.show', $product->slug));
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee($product->name);
        $detailResponse->assertSee('Lyon Silk-Mikado & 950 Solid Platinum');

        // Add to cart
        $cartResponse = $this->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $cartResponse->assertRedirect(route('cart.index'));
        $this->get(route('cart.index'))->assertSee($product->name);
    }
}
