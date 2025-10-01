<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CustomerController;

Route::get('/ping', function () {
    return response()->json(['message' => 'API is working!']);
});

// CARA YANG SIMPLE
//Route::apiResource('customers', CustomerController::class);

// CARA MANUAL
Route::get('customers', [CustomerController::class, 'index']);
Route::post('customers', [CustomerController::class, 'store']);
Route::get('customers/{id}', [CustomerController::class, 'show']);
Route::patch('customers/{id}', [CustomerController::class, 'update']);
Route::delete('customers/{id}', [CustomerController::class, 'destroy']);