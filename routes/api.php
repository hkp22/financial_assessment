<?php

use App\Http\Controllers\AccountsController;
use App\Http\Controllers\CustomerController;
use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/new-customer', [CustomerController::class, 'store']);
Route::post('/new-account/{customer}', [AccountsController::class, 'store']);
Route::get('/balance/{account}', [AccountsController::class, 'balance']);
Route::get('/account-history/{account}', [AccountsController::class, 'history']);
Route::post('/transfer-amount', [AccountsController::class, 'transferAmounts']);