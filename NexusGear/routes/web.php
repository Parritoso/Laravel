<?php

use App\Http\Controllers\ProductController;
use App\Models\Producto;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home', [
        'featuredProducts' => Producto::where('destacado', true)->orderBy('nombre')->take(4)->get(),
    ]);
});

Route::get('/home', function() {
    if (Auth::user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return view('home', [
        'featuredProducts' => Producto::where('destacado', true)->orderBy('nombre')->take(4)->get(),
    ]);
})->middleware(['auth','verified']);

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/admin/products', function () {
        return view('admin.products.index');
    })->name('admin.products.index');

    Route::get('/admin/categories', function () {
        return view('admin.categories.index');
    })->name('admin.categories.index');

    Route::get('/admin/discounts', function () {
        return view('admin.discounts.index');
    })->name('admin.discounts.index');

    Route::get('/admin/orders', function () {
        return view('admin.orders.index');
    })->name('admin.orders.index');
});

Route::get('/productos', [ProductController::class, 'index'])->name('products.index');
Route::get('/productos/{producto}', [ProductController::class, 'show'])->name('products.show');
