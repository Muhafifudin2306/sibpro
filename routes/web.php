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

Route::prefix('income')->group(function () {
    Route::prefix('credit')->group(function () {
        Route::get('/', [App\Http\Controllers\CreditController::class, 'index'])->name('credit');
        Route::get('/detail/{id}', [App\Http\Controllers\CreditController::class, 'detail'])->name('detailcredit');
    });
});

Route::prefix('account')->group(function () {
    Route::prefix('users')->group(function () {
        Route::group(['middleware' => ['can:access-userList']], function () {
            Route::get('/', [App\Http\Controllers\ProfileController::class, 'users'])->name('users');
        });
    });
    Route::prefix('profile')->group(function () {
        Route::group(['middleware' => ['can:access-userProfile']], function () {
            Route::get('/', [App\Http\Controllers\ProfileController::class, 'profile'])->name('profile');
        });
    });
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
        Route::get('/addRelation', [App\Http\Controllers\AttributeController::class, 'add'])->name('addRelation');
        Route::post('/storeRelation', [App\Http\Controllers\AttributeController::class, 'storeRelation'])->name('storeRelation');
        Route::post('/add', [App\Http\Controllers\AttributeController::class, 'store'])->name('storeAttribute');
        Route::post('/update/{id}', [App\Http\Controllers\AttributeController::class, 'update'])->name('updateAttribute');
        Route::delete('/delete/{id}', [App\Http\Controllers\AttributeController::class, 'destroy'])->name('deleteAttribute');
        Route::delete('/deleteRelation/{category}', [App\Http\Controllers\AttributeController::class, 'destroyRelation'])->name('deleteRelation');
    });

    Route::prefix('category')->group(function () {
        Route::post('/add', [App\Http\Controllers\CategoryController::class, 'store'])->name('storeCategory');
        Route::post('/update/{id}', [App\Http\Controllers\CategoryController::class, 'update'])->name('updateCategory');
        Route::delete('/delete/{id}', [App\Http\Controllers\CategoryController::class, 'destroy'])->name('deleteCategory');
    });

    Route::prefix('class')->group(function () {
        Route::get('/', [App\Http\Controllers\StudentClassController::class, 'index'])->name('class');
        Route::post('/add', [App\Http\Controllers\StudentClassController::class, 'store'])->name('storeClass');
        Route::post('/update/{id}', [App\Http\Controllers\StudentClassController::class, 'update'])->name('updateClass');
        Route::delete('/delete/{id}', [App\Http\Controllers\StudentClassController::class, 'destroy'])->name('deleteClass');
    });

    Route::prefix('student')->group(function () {
        Route::get('/', [App\Http\Controllers\StudentController::class, 'index'])->name('student');
        Route::get('/add', [App\Http\Controllers\StudentController::class, 'add'])->name('addStudent');
        Route::post('/store', [App\Http\Controllers\StudentController::class, 'store'])->name('storeStudent');
        Route::delete('/delete/{id}', [App\Http\Controllers\StudentController::class, 'destroy'])->name('deleteStudent');
        Route::post('/update/{id}', [App\Http\Controllers\StudentController::class, 'update'])->name('updateStudent');
        Route::post('/update/allClass/{id}', [App\Http\Controllers\StudentController::class, 'updateAllClass'])->name('updateAllClass');
        Route::delete('/delete/allStudent/{id}', [App\Http\Controllers\StudentController::class, 'destroyAllStudent'])->name('deleteAllStudent');
    });

    Route::prefix('credit')->group(function () {
        Route::post('/add', [App\Http\Controllers\CreditController::class, 'store'])->name('storeCredit');
        Route::post('/update/{id}', [App\Http\Controllers\CreditController::class, 'update'])->name('updateCredit');
        Route::delete('/delete/{id}', [App\Http\Controllers\CreditController::class, 'destroy'])->name('deleteCredit');
    });
});
