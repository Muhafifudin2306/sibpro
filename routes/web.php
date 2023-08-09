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

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();
Route::post('/readall', [App\Http\Controllers\NotificationController::class, 'store'])->name('storeNotification');
Route::post('/readnotif/{id}', [App\Http\Controllers\NotificationController::class, 'update'])->name('updateNotification');
Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notif');
Route::prefix('pengeluaran')->group(function () {

    Route::get('/vendor', [App\Http\Controllers\VendorController::class, 'index'])->name('vendor');
});

Route::prefix('setting')->group(function () {
});

Route::prefix('setting')->group(function () {
    Route::prefix('year')->group(function () {
        Route::get('/', [App\Http\Controllers\YearController::class, 'index'])->name('year');
        Route::post('/add', [App\Http\Controllers\YearController::class, 'store'])->name('storeYear');
        Route::post('/update/{id}', [App\Http\Controllers\YearController::class, 'update'])->name('updateYear');
        Route::delete('/delete/{id}', [App\Http\Controllers\YearController::class, 'destroy'])->name('deleteYear');
    });

    Route::prefix('attribute')->group(function () {
        Route::get('/', [App\Http\Controllers\AttributeController::class, 'index'])->name('attribute');
        Route::post('/add', [App\Http\Controllers\AttributeController::class, 'store'])->name('storeAttribute');
        Route::post('/update/{id}', [App\Http\Controllers\AttributeController::class, 'update'])->name('updateAttribute');
        Route::delete('/delete/{id}', [App\Http\Controllers\AttributeController::class, 'destroy'])->name('deleteAttribute');
    });

    Route::prefix('class')->group(function () {
        Route::get('/', [App\Http\Controllers\StudentClassController::class, 'index'])->name('class');
        Route::post('/add', [App\Http\Controllers\StudentClassController::class, 'store'])->name('storeClass');
        Route::post('/update/{id}', [App\Http\Controllers\StudentClassController::class, 'update'])->name('updateClass');
        Route::delete('/delete/{id}', [App\Http\Controllers\StudentClassController::class, 'destroy'])->name('deleteClass');
    });
});
