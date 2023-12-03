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

//Authentication.
Route::prefix('auth')->group(function ()
{
    Route::post('signin', [AuthenticationController::class, 'signin']);

    Route::post('signup', [AuthenticationController::class, 'signup']);

    Route::post('signup/verify/{verify_token}', [AuthenticationController::class, 'verifyEmail']);
});


//Accounts.
Route::get('/account/{id}', [AccountController::class, 'show'])->middleware('session');


//Passwords.
Route::prefix('password')->controller(PasswordController::class)->middleware('session')->group(function ()
{

    //Create password.
    Route::post('', 'store');

    //Delete password.
    Route::delete('/{id}', 'delete');

    //Update password.
    Route::put('/{id}', 'update');

    //Get password.
    Route::get('/{id}', 'show');

    //Get all client passwords
    Route::get('', 'index');
});
