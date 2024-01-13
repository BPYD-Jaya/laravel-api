<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProvinceController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\ProductController;


// Category routes

// Read
Route::apiResource('categories', CategoryController::class);

// Province routes
Route::apiResource('provinces', ProvinceController::class);


// City routes
Route::apiResource('cities', CityController::class);

// Product routes
Route::apiResource('products', ProductController::class);



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
