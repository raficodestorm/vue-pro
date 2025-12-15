<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Admin\LocationController;
use App\Http\Controllers\Api\Admin\RouteController;
use App\Http\Controllers\Api\Admin\BustypeController;

// =========================
// Public Routes
// =========================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::post('/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();
    return response()->json(['message' => 'Logged out successfully']);
})->middleware('auth:sanctum');


// =========================
// Authenticated User Route
// =========================
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// =========================
// Admin Protected Routes
// =========================
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {

    // Bustype CRUD
    Route::get('admin/bustypes', [BustypeController::class, 'index']);
    Route::post('admin/bustypes', [BustypeController::class, 'store']);
    Route::get('admin/bustypes/{id}', [BustypeController::class, 'show']);
    Route::delete('admin/bustypes/{id}', [BustypeController::class, 'destroy']);

    // Route CRUD
    Route::get('admin/routes', [RouteController::class, 'index']);
    Route::post('admin/routes', [RouteController::class, 'store']);
    Route::get('admin/routes/{id}', [RouteController::class, 'show']);
    Route::put('admin/routes/{id}', [RouteController::class, 'update']);
    Route::delete('admin/routes/{id}', [RouteController::class, 'destroy']);

    Route::get('admin/locations', [LocationController::class, 'index']);
    Route::post('admin/locations', [LocationController::class, 'store']);
    Route::get('admin/locations/{id}', [LocationController::class, 'show']);
    Route::put('admin/locations/{id}', [LocationController::class, 'update']);
    Route::delete('admin/locations/{id}', [LocationController::class, 'destroy']);
});
