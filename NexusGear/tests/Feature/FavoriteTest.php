<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Descuento;
use App\Models\Producto;
use App\Models\Rol;
use App\Models\User;
use App\Notifications\ProductAlertNotification;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
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

    public function test_favorite_alerts_follow_price_stock_and_discount_rules(): void
    {
        $admin = $this->adminUser();
        $category = Categoria::updateOrCreate(
            ['slug' => 'office'],
            ['nombre' => 'Office', 'slug' => 'office']
        );
        $product = Producto::create([
            'nombre' => 'Alert Test',
            'precio' => 100,
            'descripcion' => 'Producto para probar alertas de favoritos.',
            'stock' => 20,
            'destacado' => false,
        ]);
        $product->categorias()->sync([$category->id]);

        $highThresholdUser = User::factory()->create();
        $lowThresholdUser = User::factory()->create();
        $this->createFavorite($highThresholdUser, $product, 10);
        $this->createFavorite($lowThresholdUser, $product, 3);

        Notification::fake();
        $this->actingAs($admin)->put(route('admin.products.update', $product), $this->productPayload($product, $category, [
            'precio' => 80,
        ]));
        $this->assertAlertSent($highThresholdUser, 'precio');
        $this->assertAlertSent($lowThresholdUser, 'precio');

        Notification::fake();
        $product->refresh();
        $this->actingAs($admin)->put(route('admin.products.update', $product), $this->productPayload($product, $category, [
            'precio' => 120,
        ]));
        Notification::assertNothingSent();

        Notification::fake();
        $product->refresh();
        $this->actingAs($admin)->put(route('admin.products.update', $product), $this->productPayload($product, $category, [
            'stock' => 0,
        ]));
        $this->assertAlertSent($highThresholdUser, 'stock_agotado');
        $this->assertAlertSent($lowThresholdUser, 'stock_agotado');

        Notification::fake();
        $product->refresh();
        $this->actingAs($admin)->put(route('admin.products.update', $product), $this->productPayload($product, $category, [
            'stock' => 7,
        ]));
        $this->assertAlertSent($highThresholdUser, 'stock_disponible');
        $this->assertAlertSent($lowThresholdUser, 'stock_disponible');

        Notification::fake();
        $product->refresh();
        $this->actingAs($admin)->put(route('admin.products.update', $product), $this->productPayload($product, $category, [
            'stock' => 20,
        ]));
        Notification::assertNothingSent();

        Notification::fake();
        $product->refresh();
        $this->actingAs($admin)->put(route('admin.products.update', $product), $this->productPayload($product, $category, [
            'stock' => 5,
        ]));
        $this->assertAlertSent($highThresholdUser, 'stock_bajo');
        $this->assertAlertNotSent($lowThresholdUser, 'stock_bajo');

        Notification::fake();
        $discount = Descuento::create([
            'codigo' => 'ALERTA20',
            'tipo' => 'porcentaje',
            'valor' => 20,
            'fecha_fin' => now()->addWeek(),
        ]);

        $product->refresh();
        $this->actingAs($admin)->put(route('admin.products.update', $product), $this->productPayload($product, $category, [
            'descuento_id' => $discount->id,
        ]));
        $this->assertAlertSent($highThresholdUser, 'precio');
        $this->assertAlertSent($lowThresholdUser, 'precio');
    }

    public function test_notification_read_route_marks_it_and_redirects_to_product(): void
    {
        $this->seed(ProductSeeder::class);
        $user = User::factory()->create();
        $product = Producto::firstOrFail();
        $id = (string) Str::uuid();

        DB::table('notifications')->insert([
            'id' => $id,
            'type' => ProductAlertNotification::class,
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
            'data' => json_encode([
                'producto_id' => $product->id,
                'tipo' => 'precio',
                'mensaje' => 'El producto ha bajado de precio.',
                'url' => route('products.show', $product),
            ]),
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($user)
            ->get(route('notifications.read', $id))
            ->assertRedirect(route('products.show', $product));

        $this->assertNotNull(DB::table('notifications')->where('id', $id)->value('read_at'));
    }

    private function adminUser(): User
    {
        $admin = User::factory()->create();
        $role = Rol::create(['nombre_rol' => 'admin']);
        $admin->roles()->attach($role->id, ['asignado_el' => now()]);

        return $admin;
    }

    private function createFavorite(User $user, Producto $product, int $threshold): void
    {
        $user->favoritos()->create([
            'producto_id' => $product->id,
            'agregado_el' => now(),
            'umbral_stock' => $threshold,
        ]);
    }

    private function productPayload(Producto $product, Categoria $category, array $override = []): array
    {
        return [
            'nombre' => $product->nombre,
            'precio' => $product->precio,
            'descripcion' => $product->descripcion,
            'stock' => $product->stock,
            'categorias' => [$category->id],
            'destacado' => $product->destacado ? '1' : null,
            ...$override,
        ];
    }

    private function assertAlertSent(User $user, string $type): void
    {
        Notification::assertSentTo($user, ProductAlertNotification::class, function ($notification) use ($user, $type) {
            return $notification->toArray($user)['tipo'] === $type;
        });
    }

    private function assertAlertNotSent(User $user, string $type): void
    {
        Notification::assertNotSentTo($user, ProductAlertNotification::class, function ($notification) use ($user, $type) {
            return $notification->toArray($user)['tipo'] === $type;
        });
    }
}
