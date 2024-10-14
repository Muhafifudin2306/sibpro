<?php

use App\Http\Controllers\TagihanController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserImportController;

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
Route::post('/export-realisasi', [App\Http\Controllers\HomeController::class, 'exportRealisasi'])->name('exportRealisasi');
Route::get('/get-admin-count', [App\Http\Controllers\HomeController::class, 'getAdminCount'])->name('adminCount');

Auth::routes();
Route::post('/readall', [App\Http\Controllers\NotificationController::class, 'store'])->name('storeNotification');
Route::post('/readnotif/{id}', [App\Http\Controllers\NotificationController::class, 'update'])->name('updateNotification');
Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notif');
Route::post('/current-year', [App\Http\Controllers\YearController::class, 'currentYear'])->name('current-year');

Route::prefix('pengeluaran')->group(function () {

    Route::get('/vendor', [App\Http\Controllers\VendorController::class, 'index'])->name('vendor');
});


Route::get('/import-users', [UserImportController::class, 'showImportForm'])->name('import.users.form');
Route::post('/import-users', [UserImportController::class, 'import'])->name('import.users');


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
    Route::group(['middleware' => ['can:access-studentUpdate']], function () {
        Route::post('/update/{uuid}', [App\Http\Controllers\StudentController::class, 'updateStudent'])->name('updateStudent');
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
    Route::prefix('bahan')->group(function () {
        Route::get('/', [App\Http\Controllers\BahanController::class, 'index'])->name('bahanSpending');
        Route::post('/store', [App\Http\Controllers\BahanController::class, 'store'])->name('storeBahan');
        Route::delete('/delete/{id}', [App\Http\Controllers\BahanController::class, 'destroy'])->name('deleteBahan');
        Route::post('/update/{id}', [App\Http\Controllers\BahanController::class, 'update'])->name('updateBahan');
    });

    Route::prefix('operasional')->group(function () {
        Route::get('/', [App\Http\Controllers\ExternalSpendingController::class, 'indexOperasional'])->name('indexOperasionl');
        Route::post('/store', [App\Http\Controllers\ExternalSpendingController::class, 'storeOperasional'])->name('storeOperasional');
        Route::delete('/delete/{id}', [App\Http\Controllers\ExternalSpendingController::class, 'destroy'])->name('deleteOperasional');
        Route::post('/update/{id}', [App\Http\Controllers\ExternalSpendingController::class, 'updateOperasional'])->name('updateOperasional');
    });

    Route::prefix('non-operasional')->group(function () {
        Route::get('/', [App\Http\Controllers\ExternalSpendingController::class, 'indexNonOperasional'])->name('indexNonOperasional');
        Route::post('/store', [App\Http\Controllers\ExternalSpendingController::class, 'storeNonOperasional'])->name('storeNonOperasional');
        Route::delete('/delete/{id}', [App\Http\Controllers\ExternalSpendingController::class, 'destroy'])->name('deleteNonOperasional');
        Route::post('/update/{id}', [App\Http\Controllers\ExternalSpendingController::class, 'updateNonOperasional'])->name('updateNonOperasional');
    });
});


Route::prefix('savings')->group(function () {
    Route::get('/{uuid}', [App\Http\Controllers\SavingController::class, 'index'])->name('indexSavings');
});


Route::prefix('credit')->group(function () {
    Route::get('/', [App\Http\Controllers\CreditController::class, 'index'])->name('credit');
    Route::get('/add', [App\Http\Controllers\CreditController::class, 'addPage'])->name('addCredit');
    Route::get('/generate', [App\Http\Controllers\CreditController::class, 'generateCredit'])->name('generateCredit');
    Route::post('/store', [App\Http\Controllers\CreditController::class, 'storeSPP'])->name('storeSPP');
    Route::get('/detail/{uuid}', [App\Http\Controllers\CreditController::class, 'detail'])->name('detailcredit');
    Route::get('/detail/student/{uuid}', [App\Http\Controllers\CreditController::class, 'billingStudent'])->name('billingStudent');
    Route::get('/payment/{uuid}', [App\Http\Controllers\CreditController::class, 'payment'])->name('paymentCredit');
});

Route::prefix('transaction')->group(function () {
    Route::get('/recent', [App\Http\Controllers\PaymentController::class, 'allTransaction'])->name('allTransaction');
});
Route::prefix('today')->group(function () {
    Route::get('/transaction', [App\Http\Controllers\PaymentController::class, 'todayTransaction'])->name('todayTransaction');
});
Route::prefix('income')->group(function () {

    Route::prefix('payment')->group(function () {
        Route::get('/all', [App\Http\Controllers\PaymentController::class, 'allData'])->name('allData');
        Route::get('/confirm/{uuid}', [App\Http\Controllers\PaymentController::class, 'confirmXendit'])->name('paymentXendit');
        Route::delete('/delete/{invoice_number}', [App\Http\Controllers\PaymentController::class, 'destroyPayment'])->name('deletePay');
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
            Route::get('/edit/{uuid}', [App\Http\Controllers\ProfileController::class, 'editUser'])->name('editUser');
        });
        Route::group(['middleware' => ['can:access-userUpdate']], function () {
            Route::post('/update/{uuid}', [App\Http\Controllers\ProfileController::class, 'updateUser'])->name('updateUser');
        });
        Route::group(['middleware' => ['can:access-passwordUpdate']], function () {
            Route::post('/update-password/{uuid}', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('updatePassword');
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
            Route::get('/role/add', [App\Http\Controllers\SecurityController::class, 'addRole'])->name('addRole');
        });

        Route::group(['middleware' => ['can:access-roleAdd']], function () {
            Route::post('/role/store', [App\Http\Controllers\SecurityController::class, 'storeRole'])->name('storeRole');
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
        Route::group(['middleware' => ['can:access-packageList']], function () {
            Route::get('/download/excel', [App\Http\Controllers\PackageController::class, 'downloadExcel'])->name('Excelpackages');
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

Route::prefix('cart')->group(function () {
    Route::get('/', [App\Http\Controllers\PaymentController::class, 'indexCart']);
    Route::post('/offline', [App\Http\Controllers\PaymentController::class, 'addToCart']);
    Route::post('/delete', [App\Http\Controllers\PaymentController::class, 'deleteCart']);
});

Route::prefix('payment')->group(function () {
    Route::get('/', [App\Http\Controllers\PaymentController::class, 'indexPayment']);
    Route::post('/confirm', [App\Http\Controllers\PaymentController::class, 'confirmPayment']);
    Route::get('/cancel/{uuid}', [App\Http\Controllers\PaymentController::class, 'cancelPayment']);
    Route::get('/reject/{id}', [App\Http\Controllers\PaymentController::class, 'rejectPayment']);
    Route::get('/invoice/{invoice_number}', [App\Http\Controllers\CreditController::class, 'InvoicePage']);
    Route::get('/proccess/{uuid}', [App\Http\Controllers\CreditController::class, 'InvoiceProccess']);
});

Route::prefix('enrollment')->group(function () {
    // Route::get('/', [App\Http\Controllers\EnrollmentController::class, 'index'])->name('enrollment');]
    Route::get('/{id}', [App\Http\Controllers\EnrollmentController::class, 'index']);
    Route::get('/detail/{uuid}', [App\Http\Controllers\EnrollmentController::class, 'detail'])->name('detailenrollment');
    Route::get('/detail/student/{uuid}', [App\Http\Controllers\EnrollmentController::class, 'billingStudent'])->name('enrollmentStudent');
    Route::post('/process-multiple-payments', [App\Http\Controllers\EnrollmentController::class, 'processMultiplePayments'])->name('processMultiplePayments');
    Route::post('/process-invoice-number', [App\Http\Controllers\EnrollmentController::class, 'invoiceNumber'])->name('invoiceNumber');
    Route::post('/update/{id}', [App\Http\Controllers\EnrollmentController::class, 'editData']);
    Route::delete('/delete/{id}', [App\Http\Controllers\EnrollmentController::class, 'destroy'])->name('deletePayment');
    Route::post('/tagihan', [App\Http\Controllers\TagihanController::class, 'store'])->name('tagihan.store');
    Route::get('/edit/{id}', [App\Http\Controllers\EnrollmentController::class, 'editPayment'])->name('editEnrollment');
});

Route::prefix('payment-done')->group(function () {
    Route::get('/', [App\Http\Controllers\PaymentController::class, 'indexPaymentDone']);
    Route::get('/detail/{id}', [App\Http\Controllers\PaymentController::class, 'detailPaymentDone']);
    Route::get('/kwitansi/{invoice_number}/{user_id}', [App\Http\Controllers\PaymentController::class, 'detailKwitansiDone']);
    Route::get('/print/payment/{id}', [App\Http\Controllers\PaymentController::class, 'printPaymentDone']);
    Route::get('/print/kwitansi/{invoice_number}/{user_id}', [App\Http\Controllers\PaymentController::class, 'printKwitansiDone']);
});

Route::prefix('petugas')->group(function () {
    Route::get('/', [App\Http\Controllers\PetugasController::class, 'index']);
    Route::post('/add', [App\Http\Controllers\PetugasController::class, 'store'])->name('storePetugas');
    Route::delete('/delete/{id}', [App\Http\Controllers\PetugasController::class, 'destroy'])->name('deletePetugas');
});