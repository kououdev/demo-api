<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\TicketController;

Route::get('/ping', function () {
    return response()->json(['message' => 'API is working!']);
});

// CARA YANG SIMPLE
//Route::apiResource('customers', CustomerController::class);
//Route::apiResource('tickets', TicketController::class);

// CARA MANUAL
Route::get('customers', [CustomerController::class, 'index']);
Route::post('customers', [CustomerController::class, 'store']);
Route::get('customers/{id}', [CustomerController::class, 'show']);
Route::patch('customers/{id}', [CustomerController::class, 'update']);
Route::delete('customers/{id}', [CustomerController::class, 'destroy']);

// CARA MANUAL
Route::get('tickets', [TicketController::class, 'index']);
Route::post('tickets', [TicketController::class, 'store']);
Route::get('tickets/{id}', [TicketController::class, 'show']);
Route::patch('tickets/{id}', [TicketController::class, 'update']);
Route::delete('tickets/{id}', [TicketController::class, 'destroy']);
