<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\AuthController;

Route::get('/ping', function () {
    return response()->json(['message' => 'API is working!']);
});

// AUTH ROUTES (Public - No authentication required)
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// PROTECTED ROUTES (Require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Auth user routes
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);

    // Customer routes
    Route::get('customers', [CustomerController::class, 'index']);
    Route::post('customers', [CustomerController::class, 'store']);
    Route::get('customers/{id}', [CustomerController::class, 'show']);
    Route::patch('customers/{id}', [CustomerController::class, 'update']);
    Route::delete('customers/{id}', [CustomerController::class, 'destroy']);

    // Ticket routes
    Route::get('tickets', [TicketController::class, 'index']);
    Route::post('tickets', [TicketController::class, 'store']);
    Route::get('tickets/{id}', [TicketController::class, 'show']);
    Route::patch('tickets/{id}', [TicketController::class, 'update']);
    Route::delete('tickets/{id}', [TicketController::class, 'destroy']);
});
