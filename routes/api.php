<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProvinceController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\ProductController;


// Category routes
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
Route::post('/categories', [CategoryController::class, 'store']);
Route::put('/categories/{id}', [CategoryController::class, 'update']);
Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

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

// Contoh Symlink Category
Route::get('/images/category/{imageName}', function ($imageName) {
    $imagePath = public_path('images/category_image/' . $imageName);

    if (File::exists($imagePath)) {
        $file = File::get($imagePath);
        $type = File::mimeType($imagePath);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    } else {
        // Handle file not found
        return response()->json(['error' => 'Image not found'], 404);
    }
});
