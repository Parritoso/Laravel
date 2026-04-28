<?php

namespace Tests\Feature;

use App\Models\Producto;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_lists_seeded_products(): void
    {
        $this->seed(ProductSeeder::class);

        $this->get(route('products.index'))
            ->assertOk()
            ->assertSee('Nexus Vertical Pro')
            ->assertSee('Aqua Keys 60');
    }

    public function test_catalog_can_filter_by_profile(): void
    {
        $this->seed(ProductSeeder::class);

        $this->get(route('products.index', ['profile' => 'gamer']))
            ->assertOk()
            ->assertSee('Aqua Keys 60')
            ->assertDontSee('Nexus Vertical Pro');
    }

    public function test_product_detail_shows_product_information(): void
    {
        $this->seed(ProductSeeder::class);

        $product = Producto::where('nombre', 'Nexus Vertical Pro')->firstOrFail();

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee($product->nombre)
            ->assertSee($product->precio_formateado);
    }
}
