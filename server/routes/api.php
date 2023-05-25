<?php

use App\Http\Controllers\MerchantController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
        Route::patch('update', [ProductController::class, 'update']);
        Route::delete('delete', [ProductController::class, 'destroy']);
    });

    // Transaction Routes
    Route::Group(['prefix' => 'transaction'], function() {
        Route::post('new', [TransactionController::class, 'store']);
        Route::patch('update', [TransactionController::class, 'update']);
        Route::delete('delete', [TransactionController::class, 'destroy']);
    });

    // Merchant Routes
    Route::Group(['prefix' => 'merchant'], function() {
        Route::get('/', [MerchantController::class, 'index']);
        Route::post('/register', [MerchantController::class, 'registerAsMerchant']);
    });

     // Payment Routes - No Login
     Route::Group(['prefix' => 'payment'], function() {
        Route::post('new', [PaymentController::class, 'store']);
        Route::patch('update', [PaymentController::class, 'update']);
        Route::delete('delete', [PaymentController::class, 'destroy']);
    });    
});
