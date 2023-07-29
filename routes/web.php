<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();
Route::prefix('pengeluaran')->group(function () {

    Route::get('/vendor', [App\Http\Controllers\VendorController::class, 'index'])->name('vendor');
});

Route::prefix('setting')->group(function () {
    Route::get('/year', [App\Http\Controllers\YearController::class, 'index'])->name('year');
    Route::post('/year/{id}', [App\Http\Controllers\YearController::class, 'update'])->name('updateYear');
});
