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

Route::get('/productos', function () {
    return "Listado de productos";
})->name('products.index');