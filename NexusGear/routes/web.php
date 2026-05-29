<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\CategoriaController as AdminCategoriaController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\DiscountController as AdminDiscountController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DireccionController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ComentarioController;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// Portada pública con una selección corta de productos destacados.
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
})->middleware(['auth','verified'])->name('home');

// Rutas del panel interno. Se agrupan para compartir prefijo, nombre y middleware de administrador.
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/dashboard', function () {
        return view('admin.dashboard', [
            'productCount' => Producto::count(),
            'lowStockCount' => Producto::where('stock', '<=', 5)->count(),
            'orderCount' => Pedido::count(),
            'pendingOrderCount' => Pedido::whereIn('estado', ['pendiente', 'procesando'])->count(),
            'recentOrders' => Pedido::with('usuario', 'factura')->latest('fecha')->take(5)->get(),
            'lowStockProducts' => Producto::orderBy('stock')->take(5)->get(),
            'topFavorites' => DB::table('v_productos_mas_favoritos')->where('favoritos_count', '>', 0)->orderByDesc('favoritos_count')->limit(5)->get(),
            'topSales' => DB::table('v_ventas_por_producto')->where('unidades_vendidas', '>', 0)->orderByDesc('ingresos_totales')->limit(5)->get(),
            'ordersByStatus' => DB::table('v_resumen_pedidos_por_estado')->get()->keyBy('estado'),
        ]);
    })->name('dashboard');

    Route::resource('products', AdminProductController::class)->parameters(['products' => 'producto']);
    Route::resource('categorias', AdminCategoriaController::class);
    Route::resource('discounts', AdminDiscountController::class);
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{pedido}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{pedido}', [AdminOrderController::class, 'update'])->name('orders.update');
});

// Catálogo público y ficha de producto.
Route::get('/productos', [ProductController::class, 'index'])->name('products.index');
Route::get('/productos/{producto}', [ProductController::class, 'show'])->name('products.show');

// Carrito disponible tanto para invitados como para usuarios autenticados.
Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrito/productos/{producto}', [CartController::class, 'store'])->name('cart.store');
Route::patch('/carrito/productos/{producto}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/carrito/productos/{producto}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::delete('/carrito', [CartController::class, 'clear'])->name('cart.clear');

// Operaciones que requieren cuenta verificada: compra, pedidos, favoritos, perfil y reseñas.
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');
    Route::get('/pedidos', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/pedidos/{pedido}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/favoritos', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favoritos/productos/{producto}', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favoritos/productos/{producto}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');

    Route::get('/onboarding', [OnboardingController::class, 'index'])->name('onboarding.index');
    Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');
    Route::get('/onboarding/2fa-qr', [OnboardingController::class, 'getTwoFactorQr'])->name('onboarding.2fa-qr');
    Route::get('/profile', [ProfileController::class,'show'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/direcciones', [DireccionController::class, 'store'])->name('direcciones.store');
    Route::delete('/direcciones/{direccion}', [DireccionController::class, 'destroy'])->name('direcciones.destroy');
    Route::put('/direcciones/{direccion}', [DireccionController::class, 'update'])->name('direcciones.update');
    Route::post('/productos/{producto}/comentarios', [ComentarioController::class, 'store'])->name('comentarios.store');
    Route::delete('/comentarios/{comentario}', [ComentarioController::class, 'destroy'])->name('comentarios.destroy');
});

// Cambiar la contraseña exige confirmación previa para reducir el riesgo si la sesión queda abierta.
Route::middleware(['auth', 'verified', 'password.confirm'])->group(function () {
    Route::get('/profile/password', [ProfileController::class, 'editPassword'])->name('profile.password.edit');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});
