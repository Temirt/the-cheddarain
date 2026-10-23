<?php

namespace Tests\Feature;

use App\Models\Product;
use Tests\TestCase;

class RestaurantWebsiteTest extends TestCase
{
    public function test_homepage_renders_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('The Cheddarloin');
        $response->assertSee('THE ART OF THE');
        $response->assertSee('Order Now');
        $response->assertSee('Track My Order');
    }

    public function test_menu_page_displays_categories_and_products(): void
    {
        $response = $this->get('/menu');
        $response->assertStatus(200);
        $response->assertSee('Our Artisan Menu');
        $response->assertSee('The Ultimate Cheddar Lava');
        $response->assertSee('Loaded Cheddar Bacon Fries');
    }

    public function test_product_detail_page_renders_with_customization_options(): void
    {
        $product = Product::where('slug', 'the-ultimate-cheddar-lava')->firstOrFail();
        $response = $this->get('/product/' . $product->slug);
        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee('Artisanal Brioche Bun');
    }

    public function test_api_product_options_returns_json(): void
    {
        $product = Product::where('slug', 'the-ultimate-cheddar-lava')->firstOrFail();
        $response = $this->getJson('/api/product-options/' . $product->id);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'name',
            'price',
            'options' => [
                '*' => [
                    'id',
                    'name',
                    'values' => [
                        '*' => ['id', 'name', 'price']
                    ]
                ]
            ]
        ]);
    }

    public function test_informational_pages_render_cleanly(): void
    {
        $this->get('/about')->assertStatus(200)->assertSee('Culinary Philosophy');
        $this->get('/contact')->assertStatus(200)->assertSee('Operating Hours');
        $this->get('/faq')->assertStatus(200)->assertSee('Frequently Asked Questions');
    }
}
