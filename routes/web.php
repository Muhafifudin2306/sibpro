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
Route::post('/current-year', [App\Http\Controllers\YearController::class, 'currentYear'])->name('current-year');

Route::prefix('pengeluaran')->group(function () {

    Route::get('/vendor', [App\Http\Controllers\VendorController::class, 'index'])->name('vendor');
});

Route::prefix('master')->group(function () {
    Route::prefix('pos')->group(function () {
        Route::get('/', [App\Http\Controllers\PointOfSalesController::class, 'index'])->name('indexPos');
        Route::post('/add', [App\Http\Controllers\PointOfSalesController::class, 'storePos'])->name('storePos');
        Route::delete('/delete/{id}', [App\Http\Controllers\PointOfSalesController::class, 'destroyPos'])->name('deletePos');
        Route::post('/update/{id}', [App\Http\Controllers\PointOfSalesController::class, 'updatePos'])->name('updatePos');
    });
    Route::prefix('vendor')->group(function () {
        Route::get('/', [App\Http\Controllers\VendorController::class, 'index'])->name('indexVendor');
        Route::post('/add', [App\Http\Controllers\VendorController::class, 'storeVendor'])->name('storeVendor');
        Route::delete('/delete/{id}', [App\Http\Controllers\VendorController::class, 'destroyVendor'])->name('deleteVendor');
        Route::post('/update/{id}', [App\Http\Controllers\VendorController::class, 'updateVendor'])->name('updateVendor');
    });
});

Route::prefix('spending')->group(function () {
    Route::prefix('attribute')->group(function () {
        Route::get('/', [App\Http\Controllers\SpendingController::class, 'indexAttribute'])->name('AttributeSpending');
        Route::get('/detail/{slug}', [App\Http\Controllers\SpendingController::class, 'detailAttribute'])->name('detailAttribute');
        Route::post('/add', [App\Http\Controllers\SpendingController::class, 'storeSpending'])->name('storeSpending');
        Route::delete('/delete/{id}', [App\Http\Controllers\SpendingController::class, 'destroySpending'])->name('deleteSpending');
        Route::post('/update/{id}', [App\Http\Controllers\SpendingController::class, 'updateSpending'])->name('updateSpending');
    });
    Route::prefix('debt')->group(function () {
        Route::post('/add', [App\Http\Controllers\SpendingController::class, 'storeDebt'])->name('storeDebt');
        Route::delete('/delete/{id}', [App\Http\Controllers\SpendingController::class, 'destroyDebt'])->name('deleteDebt');
        Route::post('/update/{id}', [App\Http\Controllers\SpendingController::class, 'updateDebt'])->name('updateDebt');
    });
});


Route::prefix('income')->group(function () {
    Route::prefix('credit')->group(function () {
        Route::get('/', [App\Http\Controllers\CreditController::class, 'index'])->name('credit');
        Route::get('/detail/{uuid}', [App\Http\Controllers\CreditController::class, 'detail'])->name('detailcredit');
        Route::get('/detail/student/{uuid}', [App\Http\Controllers\CreditController::class, 'billingStudent'])->name('billingStudent');
        Route::get('/payment/{uuid}', [App\Http\Controllers\CreditController::class, 'payment'])->name('paymentCredit');
    });

    Route::prefix('enrollment')->group(function () {
        Route::get('/', [App\Http\Controllers\EnrollmentController::class, 'index'])->name('enrollment');
        Route::get('/detail/{uuid}', [App\Http\Controllers\EnrollmentController::class, 'detail'])->name('detailenrollment');
        Route::get('/detail/student/{uuid}', [App\Http\Controllers\EnrollmentController::class, 'billingStudent'])->name('enrollmentStudent');
        Route::post('/process-multiple-payments', [App\Http\Controllers\EnrollmentController::class, 'processMultiplePayments'])->name('processMultiplePayments');
        Route::post('/process-invoice-number', [App\Http\Controllers\EnrollmentController::class, 'invoiceNumber'])->name('invoiceNumber');
    });

    Route::prefix('payment')->group(function () {
        Route::get('/all', [App\Http\Controllers\PaymentController::class, 'allData'])->name('allData');
    });

    Route::prefix('external')->group(function () {
        Route::get('/', [App\Http\Controllers\ExternalIncomeController::class, 'index'])->name('external');
        Route::post('/add', [App\Http\Controllers\ExternalIncomeController::class, 'storeExternal'])->name('storeExternal');
        Route::delete('/delete/{id}', [App\Http\Controllers\ExternalIncomeController::class, 'destroyExternal'])->name('deleteExternal');
        Route::post('/update/{id}', [App\Http\Controllers\ExternalIncomeController::class, 'updateExternal'])->name('updateExternal');
    });
});

Route::prefix('account')->group(function () {
    Route::prefix('users')->group(function () {
        Route::group(['middleware' => ['can:access-userList']], function () {
            Route::get('/', [App\Http\Controllers\ProfileController::class, 'userList'])->name('usersList');
        });
        Route::group(['middleware' => ['can:access-userAdd']], function () {
            Route::post('/add', [App\Http\Controllers\ProfileController::class, 'storeUser'])->name('storeUser');
        });
        Route::group(['middleware' => ['can:access-userDelete']], function () {
            Route::delete('/delete/{id}', [App\Http\Controllers\ProfileController::class, 'destroyUser'])->name('deleteUser');
        });
        Route::group(['middleware' => ['can:access-userEdit']], function () {
            Route::get('/edit/{id}', [App\Http\Controllers\ProfileController::class, 'editUser'])->name('editUser');
        });
        Route::group(['middleware' => ['can:access-userUpdate']], function () {
            Route::post('/update/{id}', [App\Http\Controllers\ProfileController::class, 'updateUser'])->name('updateUser');
        });
    });
    Route::prefix('security')->group(function () {
        Route::group(['middleware' => ['can:access-permissionList']], function () {
            Route::get('/permission', [App\Http\Controllers\SecurityController::class, 'permissionList'])->name('permission');
        });
        Route::group(['middleware' => ['can:access-permissionAdd']], function () {
            Route::post('/permission/add', [App\Http\Controllers\SecurityController::class, 'storePermission'])->name('storePermission');
        });
        Route::group(['middleware' => ['can:access-permissionDelete']], function () {
            Route::delete('/permission/delete/{id}', [App\Http\Controllers\SecurityController::class, 'destroyPermission'])->name('deletePermission');
        });
        Route::group(['middleware' => ['can:access-permissionUpdate']], function () {
            Route::post('/permission/update/{id}', [App\Http\Controllers\SecurityController::class, 'updatePermission'])->name('updatePermission');
        });

        Route::group(['middleware' => ['can:access-roleList']], function () {
            Route::get('/role', [App\Http\Controllers\SecurityController::class, 'roleList'])->name('role');
        });
        Route::group(['middleware' => ['can:access-roleAdd']], function () {
            Route::post('/role/add', [App\Http\Controllers\SecurityController::class, 'storeRole'])->name('storeRole');
        });
        Route::group(['middleware' => ['can:access-roleDelete']], function () {
            Route::delete('/role/delete/{id}', [App\Http\Controllers\SecurityController::class, 'destroyRole'])->name('deleteRole');
        });
        Route::group(['middleware' => ['can:access-roleEdit']], function () {
            Route::get('/role/edit/{id}', [App\Http\Controllers\SecurityController::class, 'editRole'])->name('editRole');
        });
        Route::group(['middleware' => ['can:access-roleUpdate']], function () {
            Route::post('/role/update/{id}', [App\Http\Controllers\SecurityController::class, 'updateRole'])->name('updateRole');
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
        Route::group(['middleware' => ['can:access-yearList']], function () {
            Route::get('/', [App\Http\Controllers\YearController::class, 'index'])->name('year');
        });
        Route::group(['middleware' => ['can:access-yearDelete']], function () {
            Route::delete('/delete/{id}', [App\Http\Controllers\YearController::class, 'destroy'])->name('deleteYear');
        });
        Route::group(['middleware' => ['can:access-yearAdd']], function () {
            Route::post('/add', [App\Http\Controllers\YearController::class, 'store'])->name('storeYear');
        });
        Route::group(['middleware' => ['can:access-yearUpdate']], function () {
            Route::post('/update/{id}', [App\Http\Controllers\YearController::class, 'update'])->name('updateYear');
        });
    });

    Route::prefix('attribute')->group(function () {
        Route::group(['middleware' => ['can:access-attributeAdd']], function () {
            Route::post('/add', [App\Http\Controllers\AttributeController::class, 'store'])->name('storeAttribute');
        });
        Route::group(['middleware' => ['can:access-attributeDelete']], function () {
            Route::delete('/delete/{id}', [App\Http\Controllers\AttributeController::class, 'destroy'])->name('deleteAttribute');
        });
        Route::group(['middleware' => ['can:access-attributeUpdate']], function () {
            Route::post('/update/{id}', [App\Http\Controllers\AttributeController::class, 'update'])->name('updateAttribute');
        });
    });

    Route::prefix('packages')->group(function () {
        Route::group(['middleware' => ['can:access-packageList']], function () {
            Route::get('/', [App\Http\Controllers\PackageController::class, 'index'])->name('packages');
        });
        Route::group(['middleware' => ['can:access-packageAdd']], function () {
            Route::get('/add', [App\Http\Controllers\PackageController::class, 'add'])->name('addPackage');
        });
        Route::group(['middleware' => ['can:access-packageStore']], function () {
            Route::post('/store', [App\Http\Controllers\PackageController::class, 'store'])->name('storePackage');
        });
        Route::group(['middleware' => ['can:access-packageDelete']], function () {
            Route::delete('/delete/{id}', [App\Http\Controllers\PackageController::class, 'destroy'])->name('deletePackage');
        });
        Route::group(['middleware' => ['can:access-packageEdit']], function () {
            Route::get('/edit/{id}', [App\Http\Controllers\PackageController::class, 'edit'])->name('editPackage');
        });
        Route::group(['middleware' => ['can:access-packageUpdate']], function () {
            Route::post('/update/{id}', [App\Http\Controllers\PackageController::class, 'update'])->name('updatePackage');
        });
    });

    Route::prefix('category')->group(function () {
        Route::group(['middleware' => ['can:access-categoryAdd']], function () {
            Route::post('/add', [App\Http\Controllers\CategoryController::class, 'store'])->name('storeCategory');
        });
        Route::group(['middleware' => ['can:access-categoryDelete']], function () {
            Route::delete('/delete/{id}', [App\Http\Controllers\CategoryController::class, 'destroy'])->name('deleteCategory');
        });
        Route::group(['middleware' => ['can:access-categoryUpdate']], function () {
            Route::post('/update/{id}', [App\Http\Controllers\CategoryController::class, 'update'])->name('updateCategory');
        });
    });

    Route::prefix('class')->group(function () {
        Route::group(['middleware' => ['can:access-classList']], function () {
            Route::get('/', [App\Http\Controllers\StudentClassController::class, 'index'])->name('class');
        });
        Route::group(['middleware' => ['can:access-classAdd']], function () {
            Route::post('/add', [App\Http\Controllers\StudentClassController::class, 'store'])->name('storeClass');
        });
        Route::group(['middleware' => ['can:access-classDelete']], function () {
            Route::delete('/delete/{id}', [App\Http\Controllers\StudentClassController::class, 'destroy'])->name('deleteClass');
        });
        Route::group(['middleware' => ['can:access-classUpdate']], function () {
            Route::post('/update/{id}', [App\Http\Controllers\StudentClassController::class, 'update'])->name('updateClass');
        });
    });

    Route::prefix('student')->group(function () {
        Route::group(['middleware' => ['can:access-studentList']], function () {
            Route::get('/', [App\Http\Controllers\StudentController::class, 'index'])->name('student');
        });
        Route::group(['middleware' => ['can:access-studentList']], function () {
            Route::get('/detail/{id}', [App\Http\Controllers\StudentController::class, 'detail'])->name('detailStudent');
        });
        Route::group(['middleware' => ['can:access-studentUpdate']], function () {
            Route::post('/update/allClass/{id}', [App\Http\Controllers\StudentController::class, 'updateAllClass'])->name('updateAllClass');
        });
        Route::group(['middleware' => ['can:access-studentDelete']], function () {
            Route::delete('/delete/allStudent/{id}', [App\Http\Controllers\StudentController::class, 'destroyAllStudent'])->name('deleteAllStudent');
        });
    });

    Route::prefix('credit')->group(function () {
        Route::group(['middleware' => ['can:access-creditAdd']], function () {
            Route::post('/add', [App\Http\Controllers\CreditController::class, 'store'])->name('storeCredit');
        });
        Route::group(['middleware' => ['can:access-creditDelete']], function () {
            Route::delete('/delete/{id}', [App\Http\Controllers\CreditController::class, 'destroy'])->name('deleteCredit');
        });
        Route::group(['middleware' => ['can:access-creditUpdate']], function () {
            Route::post('/update/{id}', [App\Http\Controllers\CreditController::class, 'update'])->name('updateCredit');
        });
    });
});

Route::prefix('payment')->group(function () {
    Route::prefix('credit')->group(function () {
        Route::get('/', [App\Http\Controllers\PaymentController::class, 'index']);
        Route::get('/detail/{id}', [App\Http\Controllers\PaymentController::class, 'detail'])->name('detailpayment');
    });

    Route::prefix('enrollment')->group(function () {
        Route::get('/', [App\Http\Controllers\EnrollmentController::class, 'index']);
    });
});
