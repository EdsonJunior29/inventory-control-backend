<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/register', [AuthController::class, 'store'])->name('auth.register');
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::group(['prefix' => 'supplier'] , function () {
        Route::get('/all-suplliers', [SupplierController::class, 'getAllSuppliers'])->name('suppliers.getAll');
        Route::get('/{id}', [SupplierController::class, 'getSupplierById'])->name('supplier.getById');
    });
});

