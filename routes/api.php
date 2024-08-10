<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::group(['prefix' => 'supplier'] , function () {
        Route::get('/all-suplliers', [SupplierController::class, 'getAllSuppliers'])->name('suppliers.getAll');
        Route::get('/{id}', [SupplierController::class, 'getSupplierById'])->name('supplier.getById');
        Route::delete('/{id}', [SupplierController::class, 'deleteSupplierById'])->name('supplier.deleteById');
    });

    Route::group(['prefix' => 'users'] , function () {
        Route::post('', [UserController::class, 'store']);
    });
});

