<?php

namespace Tests\Feature;

use App\Models\Producto;
use App\Models\User;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_must_login_to_add_products_to_cart(): void
    {
        $this->seed(ProductSeeder::class);
        $product = Producto::where('stock', '>', 0)->firstOrFail();

        $this->post(route('cart.store', $product), ['cantidad' => 1])
            ->assertRedirect(route('login'));
    }

    public function test_user_can_add_product_to_cart(): void
    {
        $this->seed(ProductSeeder::class);
        $user = User::factory()->create();
        $product = Producto::where('stock', '>', 0)->firstOrFail();

        $this->actingAs($user)
            ->post(route('cart.store', $product), ['cantidad' => 2])
            ->assertRedirect(route('cart.index'));

        $this->assertDatabaseHas('item_carrito', [
            'producto_id' => $product->id,
            'cantidad' => 2,
        ]);

        $this->actingAs($user)
            ->get(route('cart.index'))
            ->assertOk()
            ->assertSee($product->nombre)
            ->assertSee('2 productos en el carrito');
    }

    public function test_user_can_update_and_remove_cart_item(): void
    {
        $this->seed(ProductSeeder::class);
        $user = User::factory()->create();
        $product = Producto::where('stock', '>', 3)->firstOrFail();

        $this->actingAs($user)
            ->post(route('cart.store', $product), ['cantidad' => 1]);

        $this->actingAs($user)
            ->patch(route('cart.update', $product), ['cantidad' => 3])
            ->assertSessionHas('success');

        $this->assertDatabaseHas('item_carrito', [
            'producto_id' => $product->id,
            'cantidad' => 3,
        ]);

        $this->actingAs($user)
            ->delete(route('cart.destroy', $product))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('item_carrito', [
            'producto_id' => $product->id,
        ]);
    }

    public function test_cart_rejects_quantities_over_stock(): void
    {
        $this->seed(ProductSeeder::class);
        $user = User::factory()->create();
        $product = Producto::where('stock', '>', 0)->firstOrFail();

        $this->actingAs($user)
            ->post(route('cart.store', $product), ['cantidad' => $product->stock + 1])
            ->assertSessionHas('error');

        $this->assertDatabaseMissing('item_carrito', [
            'producto_id' => $product->id,
        ]);
    }
}
