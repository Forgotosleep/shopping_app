<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CartController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::Group(['namespace' => 'api'], function () {
    /* DOES NOT REQUIRE LOGIN */
    Route::get('home', [UserController::class, 'index'])->name('home');
    Route::post('login', [UserController::class, 'login'])->name('login');
    Route::post('register', [UserController::class, 'register'])->name('register');

    // Payment Routes - No Login
    Route::Group(['prefix' => 'payment'], function() {
        Route::get('/', [PaymentController::class, 'getPaymentMethods']);
    });

    // Product Routes - No Login
    Route::Group(['prefix' => 'product'], function() {
        Route::get('/', [ProductController::class, 'index']);
    });
});

Route::Group(['namespace' => 'api', 'middleware' => 'auth:sanctum'], function () {
    /* REQUIRES USER LOGIN */
    // User Routes
    Route::get('user', [UserController::class, 'fetchLoggedInData'])->name('user');
    Route::get('logout', [UserController::class, 'logout']);

    // Product Routes
    Route::Group(['prefix' => 'product'], function() {
        Route::post('new', [ProductController::class, 'store']);
        Route::post('update', [ProductController::class, 'update']);
        Route::delete('delete', [ProductController::class, 'destroy']);
    });

    // Transaction Routes
    Route::Group(['prefix' => 'transaction'], function() {
        Route::get('/', [TransactionController::class, 'listByUser']);
        // Route::get('/{trxId}', [TransactionController::class, 'displayProducts']);
        Route::get('list-merchant', [TransactionController::class, 'listByMerchant']);
        Route::post('new', [TransactionController::class, 'store']);
        Route::post('update', [TransactionController::class, 'update']);
        Route::delete('delete', [TransactionController::class, 'destroy']);
    });

    // Merchant Routes
    Route::Group(['prefix' => 'merchant'], function() {
        Route::get('/', [MerchantController::class, 'index']);
        Route::post('/register', [MerchantController::class, 'registerAsMerchant']);
    });

     // Payment Routes
     Route::Group(['prefix' => 'payment'], function() {
        Route::post('new', [PaymentController::class, 'store']);
        Route::patch('update', [PaymentController::class, 'update']);
        Route::delete('delete', [PaymentController::class, 'destroy']);
    });    

    // Cart Routes
    Route::Group(['prefix' => 'cart'], function() {
        Route::get('/', [CartController::class, 'index']);
        Route::post('add', [CartController::class, 'store']);
        Route::post('edit', [CartController::class, 'edit']);
        Route::post('select', [CartController::class, 'selectItem']);
        Route::post('unselect', [CartController::class, 'unselectItem']);
        Route::delete('/', [CartController::class, 'deleteItem']);
    });
});
