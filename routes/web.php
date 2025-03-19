<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\ClipartController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;



// ✅ HOME PAGE (PUBLIC)
Route::get('/', [HomeController::class, 'index'])->name('home');// ✅ CART PAGE (PUBLIC)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.destroy.all');
Route::get('/cart/{id}', [CartController::class, 'show'])->name('cart.item.show');


Route::get('/preview/{key}', [DesignController::class, 'preview'])->name('design.preview');

// ✅ AUTH ROUTES (LOGIN / REGISTER)
Auth::routes();

// ✅ ADMIN ROUTES (ONLY FOR ADMINS)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', ProductController::class);
    Route::resource('cliparts', ClipartController::class);
});


// ✅ PRODUCT ROUTES (PUBLIC)
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/{id}/customize', [ProductController::class, 'customize'])->name('products.customize');
Route::post('/products/{id}/customize', [ProductController::class, 'saveCustomization'])->name('products.customize.save');
Auth::routes();


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::post('/api/visitor', [App\Http\Controllers\VisitorController::class, 'createVisitor']);
