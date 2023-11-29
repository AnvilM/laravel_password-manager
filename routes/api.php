<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\PasswordController;
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

//Signin, Signup
Route::prefix('auth')->group(function(){
    Route::post('login', [AuthenticationController::class, 'login']);
    Route::post('signup', [AccountController::class, 'store']);
});



Route::middleware('session')->group(function(){

    Route::prefix('account')->controller(AccountController::class)->group(function(){
        
    });

    Route::prefix('password')->controller(PasswordController::class)->group(function(){
        Route::post('/', 'store');
    });

    Route::prefix('session')->group(function(){

    });




});