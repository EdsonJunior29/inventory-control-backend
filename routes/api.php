<?php

use App\Api\Http\Controllers\AuthController;
use App\Api\Http\Controllers\SupplierController;
use App\Api\Http\Controllers\UserController;
use App\Api\Http\Middleware\UserAccessValid;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->middleware(UserAccessValid::class);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::group(['prefix' => 'supplier'] , function () {
        Route::get('/all-suplliers', [SupplierController::class, 'getAllSuppliers'])->name('suppliers.getAll');
        Route::get('/{id}', [SupplierController::class, 'getSupplierById'])->name('supplier.getById');
        Route::delete('/{id}', [SupplierController::class, 'deleteSupplierById'])->name('supplier.deleteById');
    });

    Route::group(['prefix' => 'users'] , function () {
        Route::post('', [UserController::class, 'store'])->middleware(UserAccessValid::class);
        Route::put('/{id}', [UserController::class, 'update']);
    });
});