<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthenticationController;
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

Route::prefix('auth')->controller(AuthenticationController::class)->group(function(){
    Route::post('login', 'login');
});

Route::middleware('session')->group(function(){

    Route::prefix('account')->controller(AccountController::class)->group(function(){
        Route::get('{id}', 'show');
        Route::post('/', 'store');
    });

    Route::prefix('password')->group(function(){

    });

    Route::prefix('session')->group(function(){

    });




});