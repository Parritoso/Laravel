<?php

namespace App\Providers;

use App\Models\Carrito;
use App\Models\Producto;
use App\Services\GuestCartService;
use App\Services\Payments\PaymentGateway;
use App\Services\Payments\StripePaymentGateway;
use Illuminate\Auth\Events\Login;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(GuestCartService::class);
        $this->app->singleton(PaymentGateway::class, StripePaymentGateway::class);
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        View::composer('layouts.app', function ($view): void {
            $cartCount = 0;

            if (auth()->check()) {
                $cartCount = auth()->user()
                    ->carrito()
                    ->withSum('items as cantidad_total', 'cantidad')
                    ->first()
                    ?->cantidad_total ?? 0;
            } else {
                $cartCount = app(GuestCartService::class)->count();
            }

            $view->with('cartCount', $cartCount);
        });

        Event::listen(Login::class, function ($event): void {
            $guest = app(GuestCartService::class);
            $rawItems = $guest->items();

            if (empty($rawItems)) {
                return;
            }

            $cart = Carrito::firstOrCreate(['usuario_id' => $event->user->id]);

            foreach ($rawItems as $item) {
                $product = Producto::find($item['producto_id']);
                if (!$product || !$product->disponible) {
                    continue;
                }

                $existing = $cart->items()->where('producto_id', $item['producto_id'])->first();

                if ($existing) {
                    $newQty = min($existing->cantidad + $item['cantidad'], $product->stock);
                    $existing->update(['cantidad' => $newQty, 'precio_actual' => (float) $product->precio]);
                } else {
                    $cart->items()->create([
                        'producto_id'   => $item['producto_id'],
                        'cantidad'      => min($item['cantidad'], $product->stock),
                        'precio_actual' => (float) $product->precio,
                    ]);
                }
            }

            $guest->forget();
        });
    }
}
