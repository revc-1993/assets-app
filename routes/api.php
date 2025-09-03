<?php

use App\Http\Api\Controllers\TransactionTypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Api\Controllers\AssetController;
use App\Http\Api\Controllers\DepartmentController;
use App\Http\Api\Controllers\EmployeeController;
use App\Http\Api\Controllers\EodController;
use App\Http\Api\Controllers\TransactionController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::apiResource('assets', AssetController::class);
    Route::apiResource('departments', DepartmentController::class);
    Route::apiResource('employees', EmployeeController::class);
    Route::apiResource('eods', EodController::class);
    Route::apiResource('transactions', TransactionController::class);
    Route::apiResource('transaction_types', TransactionTypeController::class);
});
