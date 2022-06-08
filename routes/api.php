<?php

use App\Http\Controllers\{UserController, WalletController};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('user', UserController::class.'@store')->name("user.create");

Route::group(['prefix' => 'wallet'], function () {
    Route::post('/deposit', WalletController::class.'@deposit')->name("wallet.deposit");
    Route::post('/transfer', WalletController::class.'@transfer')->name("wallet.transfer");
});
