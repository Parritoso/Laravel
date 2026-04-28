<?php

namespace Tests\Feature;

use App\Mail\OrderConfirmationMail;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\User;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_checkout_cart_and_create_order(): void
    {
        Mail::fake();
        $this->seed(ProductSeeder::class);

        $user = User::factory()->create();
        $product = Producto::where('stock', '>', 2)->firstOrFail();
        $initialStock = $product->stock;

        $this->actingAs($user)
            ->post(route('cart.store', $product), ['cantidad' => 2]);

        $this->actingAs($user)
            ->post(route('checkout.store'))
            ->assertRedirect();

        $pedido = Pedido::with('factura', 'lineas')->firstOrFail();
        $subtotal = round((float) $product->precio * 2, 2);
        $iva = round($subtotal * 0.21, 2);

        $this->assertSame($user->id, $pedido->usuario_id);
        $this->assertSame('pendiente', $pedido->estado);
        $this->assertDatabaseHas('linea_pedido', [
            'pedido_id' => $pedido->id,
            'producto_id' => $product->id,
            'cantidad' => 2,
            'subtotal' => $subtotal,
        ]);
        $this->assertDatabaseHas('facturas', [
            'pedido_id' => $pedido->id,
            'subtotal' => $subtotal,
            'iva' => $iva,
            'total_factura' => round($subtotal + $iva, 2),
        ]);
        $this->assertDatabaseMissing('item_carrito', [
            'producto_id' => $product->id,
        ]);
        $this->assertSame($initialStock - 2, $product->fresh()->stock);

        Mail::assertSent(OrderConfirmationMail::class, function (OrderConfirmationMail $mail) use ($pedido) {
            return $mail->pedido->is($pedido);
        });
    }

    public function test_checkout_requires_non_empty_cart(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('checkout.store'))
            ->assertRedirect(route('cart.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseCount('pedidos', 0);
    }

    public function test_user_can_view_own_orders_and_detail(): void
    {
        Mail::fake();
        $this->seed(ProductSeeder::class);

        $user = User::factory()->create();
        $product = Producto::where('stock', '>', 0)->firstOrFail();

        $this->actingAs($user)
            ->post(route('cart.store', $product), ['cantidad' => 1]);

        $this->actingAs($user)->post(route('checkout.store'));

        $pedido = Pedido::with('factura')->firstOrFail();

        $this->actingAs($user)
            ->get(route('orders.index'))
            ->assertOk()
            ->assertSee($pedido->factura->numero_factura);

        $this->actingAs($user)
            ->get(route('orders.show', $pedido))
            ->assertOk()
            ->assertSee($product->nombre)
            ->assertSee($pedido->factura->total_formateado);
    }

    public function test_user_cannot_view_other_user_order(): void
    {
        Mail::fake();
        $this->seed(ProductSeeder::class);

        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $product = Producto::where('stock', '>', 0)->firstOrFail();

        $this->actingAs($owner)
            ->post(route('cart.store', $product), ['cantidad' => 1]);

        $this->actingAs($owner)->post(route('checkout.store'));

        $pedido = Pedido::firstOrFail();

        $this->actingAs($otherUser)
            ->get(route('orders.show', $pedido))
            ->assertForbidden();
    }
}
