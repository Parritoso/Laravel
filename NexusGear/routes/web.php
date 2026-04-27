<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/home', function() {
    if (Auth::user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return view('home');
})->middleware(['auth','verified']);

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    
    Route::get('/admin/dashboard', function () {
        return view('auth.dashboard');
    })->name('admin.dashboard');

    // Aquí irían más rutas de gestión:
    // Route::resource('productos', ProductoController::class);
});

// O si es una ruta simple de prueba por ahora:
Route::get('/productos', function () {
    return "Listado de productos";
})->name('products.index');