<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'admin' => \App\Http\Middleware\CheckAdmin::class,
        ]);
        $middleware->web(append:[
            \App\Http\Middleware\SetLocale::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->report(function (Throwable $e) {
            // 1. Generamos un código único corto y fácil de leer
            $errorRef = 'REF-' . strtoupper(Str::random(6));

            // 2. Lo compartimos globalmente con Blade para este ciclo de vida
            View::share('globalErrorRef', $errorRef);

            // 3. Lo inyectamos en el contexto del log de Laravel de forma nativa
            Log::shareContext([
                'error_ref' => $errorRef
            ]);
        });
    })->create();
