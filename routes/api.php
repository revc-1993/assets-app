<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AssetController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\EodController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\TransactionTypeController;

Route::prefix('v1')->group(function () {
    Route::get('/', function () {
        return response()->json(['message' => 'API de Gestión de Activos - Versión 1']);
    });

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/change-password', [AuthController::class, 'changePassword']);

        // Rutas para los activos
        Route::get('assets/search/{searchTerm}', [AssetController::class, 'search'])->name('assets.search');
        Route::apiResource('assets', AssetController::class)->except(['store', 'update', 'destroy']);

        // Rutas de recursos para departamentos, empleados y EOD
        Route::apiResource('departments', DepartmentController::class);
        Route::apiResource('employees', EmployeeController::class);
        Route::apiResource('eods', EodController::class);

        // Usamos una sola llamada para gestionar todas las rutas de transacciones
        Route::apiResource('transactions', TransactionController::class);

        // Rutas para los tipos de transacción
        Route::apiResource('transaction_types', TransactionTypeController::class);
    });
});
