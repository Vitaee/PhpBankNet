<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
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

Route::prefix('v1')->namespace('Api')->group( function () {

    Route::post('login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('signup', [AuthController::class, 'signup'])->name('auth.signup');

    Route::middleware('auth:api')->group(function () {

        Route::get("user", [AuthController::class, 'getInfo'])->name('user');

        Route::prefix('account')->name('account.')->group(function () {
            Route::post('deposit', [AccountController::class, 'deposit'])->name('deposit');
            Route::post('withdraw', [AccountController::class, 'withdraw'])->name('withdraw');
            Route::get('balance', [AccountController::class, 'balance'])->name('balance');
        });

    });

});
