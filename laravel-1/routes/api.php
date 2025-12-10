<?php

use App\Http\Controllers\Api\Admin\BustypeController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// =========================
// Public Routes
// =========================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// =========================
// Authenticated User Route
// =========================
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// =========================
// Admin Protected Routes
// =========================
Route::middleware(['auth:sanctum', 'admin'])->group(function () {

    // Bustype CRUD
    Route::get('bustypes', [BustypeController::class, 'index']);
    Route::post('bustypes', [BustypeController::class, 'store']);
    Route::get('bustypes/{id}', [BustypeController::class, 'show']);
    Route::put('bustypes/{id}', [BustypeController::class, 'update']);
    Route::delete('bustypes/{id}', [BustypeController::class, 'destroy']);

});
