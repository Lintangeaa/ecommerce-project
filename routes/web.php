<?php

use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BalanceController;


route::get('/', [HomeController::class, 'home']);

route::get('/dashboard', [HomeController::class, 'login_home'])->middleware(['auth', 'verified'])->name('dashboard');

route::get('/myorders', [HomeController::class, 'myorders'])->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/balance', [BalanceController::class, 'showBalance'])->middleware(['auth', 'verified'])->name('balance');
    Route::post('/balance/topup', [BalanceController::class, 'topUp'])->name('balance.topup');

    Route::get('/transactions/create', function () {
        return view('topup');
    });
    Route::post('/transactions/create', [TransactionController::class, 'createSnapToken']);
    Route::post('/pay-order', [OrderController::class, 'payOrder'])->name('pay-order');
    Route::post('/pay-with-balance', [OrderController::class, 'payWithBalance'])->name('pay.balance');

    Route::get('/remove-cart/{product_id}', [HomeController::class, 'removeItem'])->name('remove.cart');
    Route::post('/webhook/orders', [OrderController::class, 'handleOrder'])->name('webhook.orders');
    Route::get('add_cart/{id}', [HomeController::class, 'add_cart'])->name('add.cart');
    Route::get('min_cart/{id}', [HomeController::class, 'min_cart'])->name('min.cart');
});

Route::post('/webhook/midtrans', [TransactionController::class, 'handleWebhook']);

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/admin/orders/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
});

require __DIR__ . '/auth.php';


route::get('admin/dashboard', [HomeController::class, 'index'])->middleware(['auth', 'admin']);

route::get('view_category', [AdminController::class, 'view_category'])->middleware(['auth', 'admin']);

route::post('add_category', [AdminController::class, 'add_category'])->middleware(['auth', 'admin']);

route::get('delete_category/{id}', [AdminController::class, 'delete_category'])->middleware(['auth', 'admin']);

route::get('edit_category/{id}', [AdminController::class, 'edit_category'])->middleware(['auth', 'admin']);

route::post('update_category/{id}', [AdminController::class, 'update_category'])->middleware(['auth', 'admin']);

route::get('add_product', [AdminController::class, 'add_product'])->middleware(['auth', 'admin']);

route::post('upload_product', [AdminController::class, 'upload_product'])->middleware(['auth', 'admin']);

route::get('view_product', [AdminController::class, 'view_product'])->middleware(['auth', 'admin']);

route::get('delete_product/{id}', [AdminController::class, 'delete_product'])->middleware(['auth', 'admin']);

route::get('update_product/{id}', [AdminController::class, 'update_product'])->middleware(['auth', 'admin']);

route::post('edit_product/{id}', [AdminController::class, 'edit_product'])->middleware(['auth', 'admin']);

route::get('product_search', [AdminController::class, 'product_search'])->middleware(['auth', 'admin']);

route::get('product_details/{id}', [HomeController::class, 'product_details']);

route::get('mycart', [HomeController::class, 'mycart'])->middleware(['auth', 'verified']);

route::get('delete_cart/{id}', [HomeController::class, 'delete_cart'])->middleware(['auth', 'verified']);

route::post('confirm_order', [HomeController::class, 'confirm_order'])->middleware(['auth', 'verified']);

route::get('on_the_way/{id}', [AdminController::class, 'on_the_way'])->middleware(['auth', 'admin']);

route::get('delivered/{id}', [AdminController::class, 'delivered'])->middleware(['auth', 'admin']);






