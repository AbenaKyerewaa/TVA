<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FounderProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_founder_profile_page_loads_successfully_and_displays_ceo_profile(): void
    {
        $response = $this->get(route('founder'));

        $response->assertStatus(200);
        $response->assertSee('Jennifer Vaughn');
        $response->assertSee('Creative Director');
        $response->assertSee('Chief Executive Officer');
        $response->assertSee('The Architecture of Light');
        $response->assertSee('Geneva and Liverpool');
        $response->assertSee('Founding the Independent Atelier');
        $response->assertSee('Unwavering Ethical Sourcing');
        $response->assertSee('images/found.jpg');
        $response->assertSee('Place Vendôme');
    }

    public function test_about_route_renders_founder_profile(): void
    {
        $response = $this->get(route('about'));

        $response->assertStatus(200);
        $response->assertSee('Jennifer Vaughn');
        $response->assertSee('The Vaughn Atelier');
    }

    public function test_storefront_navigation_and_footer_link_to_founder_page(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee(route('founder'));
        $response->assertSee('The Founder');
    }
}
