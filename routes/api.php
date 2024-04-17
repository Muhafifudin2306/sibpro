<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/get-external-count', [App\Http\Controllers\HomeController::class, 'getExternalCount'])->name('externalCount');

// Route::post('/midtrans-callback', [App\Http\Controllers\GatewayController::class, 'callback'])->name('paymentCallback');

Route::prefix('payment')->group(function () {
    Route::post('/confirm', [App\Http\Controllers\GatewayController::class, 'confirmPaymentOnline']);
});