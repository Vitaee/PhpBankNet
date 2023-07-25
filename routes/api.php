<?php

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




Route::post('login', [AuthController::class, 'login'])->name('auth.login');
Route::post('signup', [AuthController::class, 'signup'])->name('auth.signup');


Route::prefix('v1')->namespace('Api')->group( function () {

    Route::middleware('auth:sanctum')->name('user.')->group(function () {
        Route::get("user", [AuthController::class, 'getInfo'])->name('user');
    });


    /*Route::prefix('supplier')->name('supplier.')->group(function () {
        Route::post('/', [SomeController::class, 'store'])->name('create');
        Route::get('/', [SomeController::class, 'index'])->name('getAll');
        Route::get('/{id}', [SomeController::class, 'show'])->name('get')->where(['id' => '[0-9]+']);
        Route::put('/{id}', [SomeController::class, 'update'])->name('update')->where(['id' => '[0-9]+']);
        Route::delete('/{id}', [SomeController::class, 'destroy'])->name('delete')->where(['id' => '[0-9]+']);
    });*/

});
