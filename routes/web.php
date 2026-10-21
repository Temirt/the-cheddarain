<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderTrackingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| The Cheddarain Web Routes
|--------------------------------------------------------------------------
*/

// Customer Authentication & Account Portal
Route::get('/login', [CustomerAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [CustomerAuthController::class, 'login'])->name('login.submit');
Route::get('/register', [CustomerAuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [CustomerAuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');
Route::get('/account', [CustomerAuthController::class, 'account'])->middleware('auth')->name('account');

// Customer Pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/menu', [MenuController::class, 'index'])->name('menu');
Route::get('/product/{slug}', [MenuController::class, 'show'])->name('product.detail');
Route::get('/api/product-options/{id}', [MenuController::class, 'getOptionsJson'])->name('api.product.options');

// Shopping Cart & Checkout
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/order-confirmation/{reference}', [CheckoutController::class, 'confirmation'])->name('order.confirmation');

// Full Order Tracking System (McDonald's inspired)
Route::get('/track', [OrderTrackingController::class, 'index'])->name('order.track');
Route::get('/track/{reference}', [OrderTrackingController::class, 'show'])->name('order.track.show');
Route::post('/track/{reference}/verify', [OrderTrackingController::class, 'verifyPhone'])->name('order.track.verify');

// Polling API for Live Order Status Updates (throttled to 60 requests per minute)
Route::middleware('throttle:60,1')->get('/api/orders/{reference}/track', [OrderTrackingController::class, 'apiTrack'])->name('api.order.track');

// Informational Pages
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact/submit', [HomeController::class, 'submitContact'])->name('contact.submit');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');

/*
|--------------------------------------------------------------------------
| Restaurant Staff & Admin Portal Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest Admin Auth
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Protected Admin Routes
    Route::middleware('admin')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard.home');

        // Orders Management & Tracking Transitions
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');

        // Menu & Product Management
        Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
        Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
        Route::get('/products/{id}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{id}', [AdminProductController::class, 'update'])->name('products.update');
        Route::post('/products/{id}/toggle', [AdminProductController::class, 'toggleAvailability'])->name('products.toggle');
        Route::delete('/products/{id}', [AdminProductController::class, 'destroy'])->name('products.destroy');

        // Restaurant Operational Settings
        Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
    });
});
