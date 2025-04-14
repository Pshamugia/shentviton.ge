<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\ClipartController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;



// ✅ HOME PAGE (PUBLIC)
Route::get('/', [HomeController::class, 'index'])->name('home'); // ✅ CART PAGE (PUBLIC)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.destroy.all');
Route::get('/cart/{id}', [CartController::class, 'show'])->name('cart.item.show');
Route::post('/cart/update-quantity/{id}', [CartController::class, 'updateQuantity']);
Route::get('/load-cliparts', [ClipartController::class, 'loadMore'])->name('cliparts.load');

Route::get('/admin/cliparts/{clipart}/edit', [ClipartController::class, 'edit'])->name('admin.cliparts.edit');
Route::put('/admin/cliparts/{clipart}', [ClipartController::class, 'update'])->name('admin.cliparts.update');


Route::get('/search', [App\Http\Controllers\SearchController::class, 'search'])->name('search');


Route::get('/preview/{key}', [DesignController::class, 'preview'])->name('design.preview');

// ✅ AUTH ROUTES (LOGIN / REGISTER)
Auth::routes();

// ✅ ADMIN ROUTES (ONLY FOR ADMINS)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', ProductController::class);
    Route::resource('cliparts', ClipartController::class);
    Route::put('/admin/products/{id}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::get('/admin/products', [ProductController::class, 'index'])->name('admin.products.index');

});


// ✅ PRODUCT ROUTES (PUBLIC)
Route::get('/product/view/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/{id}/customize', [ProductController::class, 'customize'])->name('products.customize');
Route::post('/products/{id}/customize', [ProductController::class, 'saveCustomization'])->name('products.customize.save');
Route::get('/products/{type}', [ProductController::class, 'showByType'])->name('products.byType');

Auth::routes();


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/terms_and_conditions', [App\Http\Controllers\HomeController::class, 'terms'])->name('terms');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
Route::get('/cart/product-ids', [CartController::class, 'productIds'])->name('cart.productIds');
Route::get('/cart/count', function () {
    $auth_id = auth()->id();
    $visitor_hash = session('v_hash');

    $count = \App\Models\Cart::where('user_id', $auth_id)
        ->orWhere('visitor_hash', $visitor_hash)
        ->count();

    return response()->json(['count' => $count]);
});


Route::post('/api/visitor/create', [App\Http\Controllers\VisitorController::class, 'createVisitor']);
Route::get('/api/visitor/check', [App\Http\Controllers\VisitorController::class, 'checkVisitor']);


/**
 * Payment routes
 */
Route::get('/payment_info', [PaymentController::class, 'info'])->name('payment.info');
Route::get('/pay', [PaymentController::class, 'pay'])->name('payment.pay');
/**
 * Route below is passed to the payment gateway. It is not a public route.
 */
Route::get('/payment/status/closed', [PaymentController::class, 'status'])->middleware('signed')->name('payment.status.closed');
Route::get('/payment/status/{id}', [PaymentController::class, 'publicStatus'])->name('payment.status.public');
Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');