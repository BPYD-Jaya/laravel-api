<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix("/v1")->group(function(){
    Route::prefix("/about")->group(function(){
        Route::get("/", [App\Http\Controllers\Api\AboutController::class, 'get']);
        Route::post("/", [App\Http\Controllers\Api\AboutController::class, 'create']);
        Route::put("/{id}", [App\Http\Controllers\Api\AboutController::class, 'update']);
    });
    
    Route::prefix("/customer")->group(function(){
        Route::get("/", [App\Http\Controllers\Api\CustomerController::class, 'get']);
        Route::post("/", [App\Http\Controllers\Api\CustomerController::class, 'firstNotification']);
    });

    Route::prefix("/supplier")->group(function(){
        Route::get("/", [App\Http\Controllers\Api\SupplierController::class, 'get']);
        Route::post("/", [App\Http\Controllers\Api\SupplierController::class, 'register']);
        Route::get("/{id}", [App\Http\Controllers\Api\SupplierController::class, 'getById']);
        Route::delete("/{id}", [App\Http\Controllers\Api\SupplierController::class, 'delete']);
        Route::post("/{id}/", [App\Http\Controllers\Api\SupplierController::class, 'addToProduct']);
    });

    Route::prefix("/notification")->group(function(){
        Route::post("/whatsapp", [App\Http\Controllers\Api\NotificationController::class, 'whatsappNotification']);
        Route::get("/email", [App\Http\Controllers\Api\NotificationController::class, 'emailNotification']);
    });
});
