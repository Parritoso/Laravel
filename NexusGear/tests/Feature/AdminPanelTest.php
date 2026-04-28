<?php

namespace Tests\Feature;

use App\Mail\OrderConfirmationMail;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Rol;
use App\Models\User;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_access_admin_products(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.products.index'))
            ->assertRedirect('/');
    }

    public function test_admin_can_create_update_and_delete_product(): void
    {
        $admin = $this->adminUser();

        $payload = [
            'nombre' => 'Ergo Test',
            'precio' => 49.95,
            'descripcion' => 'Producto preparado para pruebas de administración.',
            'stock' => 11,
            'perfil' => 'office',
            'destacado' => '1',
        ];

        $this->actingAs($admin)
            ->post(route('admin.products.store'), $payload)
            ->assertRedirect();

        $product = Producto::where('nombre', 'Ergo Test')->firstOrFail();
        $this->assertTrue($product->destacado);

        $this->actingAs($admin)
            ->put(route('admin.products.update', $product), [
                ...$payload,
                'nombre' => 'Ergo Test Pro',
                'stock' => 6,
                'destacado' => null,
            ])
            ->assertRedirect(route('admin.products.edit', $product));

        $this->assertDatabaseHas('productos', [
            'id' => $product->id,
            'nombre' => 'Ergo Test Pro',
            'stock' => 6,
            'destacado' => false,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.products.destroy', $product))
            ->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseMissing('productos', [
            'id' => $product->id,
        ]);
    }

    public function test_admin_can_view_and_update_order_status(): void
    {
        Mail::fake();
        $this->seed(ProductSeeder::class);

        $admin = $this->adminUser();
        $customer = User::factory()->create();
        $product = Producto::where('stock', '>', 0)->firstOrFail();

        $this->actingAs($customer)->post(route('cart.store', $product), ['cantidad' => 1]);
        $this->actingAs($customer)->post(route('checkout.store'));

        $order = Pedido::with('factura')->firstOrFail();

        $this->actingAs($admin)
            ->get(route('admin.orders.index'))
            ->assertOk()
            ->assertSee($order->factura->numero_factura);

        $this->actingAs($admin)
            ->get(route('admin.orders.show', $order))
            ->assertOk()
            ->assertSee($customer->email);

        $this->actingAs($admin)
            ->patch(route('admin.orders.update', $order), ['estado' => 'enviado'])
            ->assertSessionHas('success');

        $this->assertSame('enviado', $order->fresh()->estado);
        Mail::assertSent(OrderConfirmationMail::class);
    }

    private function adminUser(): User
    {
        $admin = User::factory()->create();
        $role = Rol::create(['nombre_rol' => 'admin']);
        $admin->roles()->attach($role->id, ['asignado_el' => now()]);

        return $admin;
    }
}
