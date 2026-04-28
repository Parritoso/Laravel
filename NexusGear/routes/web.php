<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\CategoriaController as AdminCategoriaController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Models\Pedido;
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

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/dashboard', function () {
        return view('admin.dashboard', [
            'productCount' => Producto::count(),
            'lowStockCount' => Producto::where('stock', '<=', 5)->count(),
            'orderCount' => Pedido::count(),
            'pendingOrderCount' => Pedido::whereIn('estado', ['pendiente', 'procesando'])->count(),
            'recentOrders' => Pedido::with('usuario', 'factura')->latest('fecha')->take(5)->get(),
            'lowStockProducts' => Producto::orderBy('stock')->take(5)->get(),
        ]);
    })->name('dashboard');

    Route::resource('products', AdminProductController::class)->parameters(['products' => 'producto']);
    Route::resource('categorias', AdminCategoriaController::class)->except('show');
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{pedido}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{pedido}', [AdminOrderController::class, 'update'])->name('orders.update');
});

Route::get('/productos', [ProductController::class, 'index'])->name('products.index');
Route::get('/productos/{producto}', [ProductController::class, 'show'])->name('products.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
    Route::post('/carrito/productos/{producto}', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/carrito/productos/{producto}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/carrito/productos/{producto}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::delete('/carrito', [CartController::class, 'clear'])->name('cart.clear');

    Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');
    Route::get('/pedidos', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/pedidos/{pedido}', [OrderController::class, 'show'])->name('orders.show');
});
