<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\NotificationController;









/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('pages.home');
// });

// Route::get('/debug-sentry', function () {
//     throw new Exception('My first Sentry error!');
// });
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/categories', [App\Http\Controllers\CategoryController::class, 'index'])->name('categories');
Route::get('/categories/{id}', [App\Http\Controllers\CategoryController::class, 'detail'])->name('categories-detail');

Route::get('/details/{id}', [App\Http\Controllers\DetailController::class, 'index'])->name('detail');
Route::post('/details/{id}', [App\Http\Controllers\DetailController::class, 'add'])->name('detail-add');

Route::post('/checkout/callback', [App\Http\Controllers\CheckoutController::class, 'callback'])->name('midtrans-callback');

Route::get('/success', [App\Http\Controllers\CartController::class, 'success'])->name('success');

Route::get('/register/success', [App\Http\Controllers\Auth\RegisterController::class, 'success'])->name('register-success');
        Route::get('/search', [App\Http\Controllers\DashboardProductController::class, 'search'])->name('search');


Route::group(['middleware' => ['auth']], function(){

    Route::get('/getNotification', [App\Http\Controllers\NotificationController::class, 'getNotification'])->name('getNotification');


    Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('cart');
    Route::delete('/cart/{id}', [App\Http\Controllers\CartController::class, 'delete'])->name('cart-delete');

    // Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    // Route::post('/send-notification', [NotificationController::class, 'sendNotification'])->name('send.notification');


    

    Route::post('/checkout', [App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout');
    
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    // Route::get('/dashboard/products', [App\Http\Controllers\DashboardProductController::class, 'index'])->name('dashboard-product');
    // Route::get('/dashboard/products/create', [App\Http\Controllers\DashboardProductController::class, 'create'])->name('dashboard-product-create');
    // Route::post('/dashboard/products', [App\Http\Controllers\DashboardProductController::class, 'store'])->name('dashboard-product-store');
    // Route::get('/dashboard/products/{id}', [App\Http\Controllers\DashboardProductController::class, 'details'])->name('dashboard-product-details');
    // Route::post('/dashboard/products/{id}', [App\Http\Controllers\DashboardProductController::class, 'update'])->name('dashboard-product-update');
    // Route::post('/dashboard/products/gallery/upload', [App\Http\Controllers\DashboardProductController::class, 'uploadGallery'])->name('dashboard-product-gallery-upload');
    // Route::get('/dashboard/products/gallery/delete/{id}', [App\Http\Controllers\DashboardProductController::class, 'deleteGallery'])->name('dashboard-product-gallery-delete');


    // Route::get('/details/{id}/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    // Route::get('/details/{product}/reviews/{review}', [App\Http\Controllers\ReviewController::class, 'show'])
    // ->name('reviews-show');
    Route::get('/details/{slug}/reviews', [App\Http\Controllers\ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/details/{slug}/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');

    Route::get('/dashboard/transactions', [App\Http\Controllers\DashboardTransactionController::class, 'index'])->name('dashboard-transaction');
    Route::get('/dashboard/transactions/{id}', [App\Http\Controllers\DashboardTransactionController::class, 'details'])->name('dashboard-transaction-details');
    Route::post('/dashboard/transactions/{id}', [App\Http\Controllers\DashboardTransactionController::class, 'update'])->name('dashboard-transaction-update');

    Route::post('/voucher/check', [VoucherController::class, 'checkVoucher'])->name('voucher.check');


    Route::get('/dashboard/account', [App\Http\Controllers\DashboardSettingController::class, 'account'])->name('dashboard-settings-account');
    Route::post('/dashboard/update/{redirect}', [App\Http\Controllers\DashboardSettingController::class, 'update'])->name('dashboard-settings-redirect');

});

// Route::get('/admin/dashboard', [App\Http\Controllers\DashboardSettingController::class, 'account'])->name('dashboard-settings-account');
Route::prefix('admin')
    // ->namespace('Admin')
    ->middleware(['auth','admin'])
    ->group(function(){
        Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin-dashboard');
        // Route::get('/admin/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'showTransactionsChart'])
        // ->name('admin.dashboard');
        Route::resource('category', 'App\Http\Controllers\Admin\CategoryController');
        Route::resource('user', 'App\Http\Controllers\Admin\UserController');
        Route::get('/dashboard/products', [App\Http\Controllers\Admin\DashboardProductsController::class, 'index'])->name('product');
        Route::get('/dashboard/products/create', [App\Http\Controllers\Admin\DashboardProductsController::class, 'create'])->name('product-create');
        // Route::post('/dashboard/{product}/restock',[App\Http\Controllers\Admin\DashboardProductsController::class, 'restock'])->name('product-restock');
        Route::post('/dashboard/products', [App\Http\Controllers\Admin\DashboardProductsController::class, 'store'])->name('dashboard-product-store');
        Route::get('/dashboard/products/{id}', [App\Http\Controllers\Admin\DashboardProductsController::class, 'details'])->name('dashboard-product-details');
        Route::post('/dashboard/products/{id}', [App\Http\Controllers\Admin\DashboardProductsController::class, 'update'])->name('dashboard-product-update');
        Route::post('/dashboard/products/gallery/upload', [App\Http\Controllers\Admin\DashboardProductsController::class, 'uploadGallery'])->name('dashboard-product-gallery-upload');
        Route::get('/dashboard/products/gallery/delete/{id}', [App\Http\Controllers\Admin\DashboardProductsController::class, 'deleteGallery'])->name('dashboard-product-gallery-delete');
        Route::delete('/dashboard/products/delete/{id}', [App\Http\Controllers\Admin\DashboardProductsController::class, 'delete'])->name('dashboard-product-delete');
        // Route::resource('product', 'App\Http\Controllers\Admin\ProductController');
        // Route::resource('product-gallery', 'App\Http\Controllers\Admin\ProductGalleryController');
        Route::get('/reviews', [App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('admin.reviews.index');
        Route::get('/reviews/print', [App\Http\Controllers\Admin\ReviewController::class, 'print'])->name('reviews.print');
        Route::get('/transactions', [App\Http\Controllers\Admin\TransactionController::class, 'index'])->name('transaction');
        Route::get('/transactions/{id}', [App\Http\Controllers\Admin\TransactionController::class, 'details'])->name('transaction-details');
        Route::post('/transactions/{id}', [App\Http\Controllers\Admin\TransactionController::class, 'update'])->name('transaction-update');
        Route::get('/customer', [App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('customer');
        Route::get('/store', [App\Http\Controllers\Admin\DashboardSettingController::class, 'store'])->name('settings-store');
        Route::post('/update/{redirect}', [App\Http\Controllers\Admin\DashboardSettingController::class, 'update'])->name('settings-redirect');


        // Route::resource('transaction', 'App\Http\Controllers\Admin\TransactionController');
    });
Auth::routes();

