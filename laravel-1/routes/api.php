<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Admin\LocationController;
use App\Http\Controllers\Api\Admin\RouteController;
use App\Http\Controllers\Api\Admin\BustypeController;
use App\Http\Controllers\Api\Admin\BusController;
use App\Http\Controllers\Api\Controller\ScheduleController;
use App\Http\Controllers\Api\Counter\BusSearchController;

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

    // Buses CRUD
    Route::get('admin/routesfetch', [RouteController::class, 'routesfetch']);
    Route::get('admin/typesfetch', [BustypeController::class, 'typesfetch']);
    Route::get('admin/buses', [BusController::class, 'index']);
    Route::post('admin/buses', [BusController::class, 'store']);
    Route::get('admin/buses/{id}', [BusController::class, 'show']);
    Route::put('admin/buses/{id}', [BusController::class, 'update']);
    Route::delete('admin/buses/{id}', [BusController::class, 'destroy']);

    // Route CRUD
    Route::get('admin/routes', [RouteController::class, 'index']);
    Route::get('admin/locationfetch', [LocationController::class, 'locationfetch']);
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

// =========================
// controller Protected Routes
// =========================
Route::middleware(['auth:sanctum', 'role:controller'])->group(function () {
    Route::get('controller/routesfetch', [RouteController::class, 'routesfetch']);
    Route::get('controller/routes/{code}', [RouteController::class, 'showByCode']);
    Route::get('controller/typesfetch', [BustypeController::class, 'typesfetch']);
    Route::get('/controller/buses/coach/{type}', [BusController::class, 'coachesByType']);

    Route::get('controller/schedules', [ScheduleController::class, 'index']);          // list

    Route::post('controller/schedules', [ScheduleController::class, 'store']);         // store
    Route::get('controller/schedules/{id}', [ScheduleController::class, 'show']);       // show
    Route::put('controller/schedules/{id}', [ScheduleController::class, 'update']);     // update
    Route::delete('controller/schedules/{id}', [ScheduleController::class, 'destroy']); // delete

});


// =========================
// counter Protected Routes
// =========================
Route::middleware(['auth:sanctum', 'role:counter'])->group(function () {
    Route::get('counter/search-bus', [BusSearchController::class, 'search']);
    Route::get('counter/locationfetch', [LocationController::class, 'locationfetch']);
    Route::get('counter/schedules/{id}', [ScheduleController::class, 'ReservationData']); 
});

// =========================
// user Protected Routes
// =========================
Route::middleware(['auth:sanctum', 'role:user'])->group(function () {});
