<?php

namespace Tests\Feature;

use App\Models\Producto;
use App\Models\User;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_must_login_to_view_favorites(): void
    {
        $this->get(route('favorites.index'))
            ->assertRedirect(route('login'));
    }

    public function test_user_can_add_and_list_favorite_products(): void
    {
        $this->seed(ProductSeeder::class);
        $user = User::factory()->create();
        $product = Producto::firstOrFail();

        $this->actingAs($user)
            ->post(route('favorites.store', $product))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('favoritos', [
            'usuario_id' => $user->id,
            'producto_id' => $product->id,
        ]);

        $this->actingAs($user)
            ->get(route('favorites.index'))
            ->assertOk()
            ->assertSee($product->nombre);
    }

    public function test_user_can_remove_favorite_products(): void
    {
        $this->seed(ProductSeeder::class);
        $user = User::factory()->create();
        $product = Producto::firstOrFail();

        $this->actingAs($user)
            ->post(route('favorites.store', $product));

        $this->actingAs($user)
            ->delete(route('favorites.destroy', $product))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('favoritos', [
            'usuario_id' => $user->id,
            'producto_id' => $product->id,
        ]);
    }

    public function test_adding_same_product_twice_keeps_one_favorite(): void
    {
        $this->seed(ProductSeeder::class);
        $user = User::factory()->create();
        $product = Producto::firstOrFail();

        $this->actingAs($user)->post(route('favorites.store', $product));
        $this->actingAs($user)->post(route('favorites.store', $product));

        $this->assertDatabaseCount('favoritos', 1);
    }
}
