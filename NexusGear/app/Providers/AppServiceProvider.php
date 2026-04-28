<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
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
            }

            $view->with('cartCount', $cartCount);
        });
    }
}
