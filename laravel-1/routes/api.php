<?php

use App\Http\Controllers\Api\Admin\BustypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('bustypes', [BustypeController::class, 'index']);
Route::post('bustypes', [BustypeController::class, 'store']);
Route::get('bustypes/{id}', [BustypeController::class, 'show']);
Route::put('bustypes/{id}', [BustypeController::class, 'update']);
Route::delete('bustypes/{id}', [BustypeController::class, 'destroy']);
