<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProvinceController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BlogCategoryController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\SymlinkController;
use App\Http\Controllers\Api\UnauthController;


// Product routes
Route::apiResource('products', ProductController::class);


Route::prefix("/about")->group(function () {
    Route::get("/{id}", [App\Http\Controllers\Api\AboutController::class, 'get']);
    Route::post("/", [App\Http\Controllers\Api\AboutController::class, 'create'])->middleware('auth:sanctum');
    Route::put("/{id}", [App\Http\Controllers\Api\AboutController::class, 'update'])->middleware('auth:sanctum');
});

Route::prefix("/customer")->group(function () {
    Route::get("/", [App\Http\Controllers\Api\CustomerController::class, 'get'])->middleware('auth:sanctum');
    Route::post("/", [App\Http\Controllers\Api\CustomerController::class, 'firstNotification']);
});

Route::prefix("/supplier")->group(function () {
    Route::get("/", [App\Http\Controllers\Api\SupplierController::class, 'get']);
    Route::post("/", [App\Http\Controllers\Api\SupplierController::class, 'register']);
    Route::get("/{id}", [App\Http\Controllers\Api\SupplierController::class, 'getById'])->middleware('auth:sanctum');
    Route::delete("/{id}", [App\Http\Controllers\Api\SupplierController::class, 'delete'])->middleware('auth:sanctum');
});

Route::prefix("/notification")->group(function () {
    Route::post("/whatsapp", [App\Http\Controllers\Api\NotificationController::class, 'whatsappNotification'])->middleware('auth:sanctum');
    Route::get("/email", [App\Http\Controllers\Api\NotificationController::class, 'emailNotification'])->middleware('auth:sanctum');
});

Route::prefix('/addtoproducts')->group(function () {
    Route::post('{id}/', [App\Http\Controllers\Api\SupplierController::class, 'addToProduct'])->middleware('auth:sanctum');
});

Route::get('/dashboard', [UserController::class, 'dashboard'])->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::get('/blogcategories', [BlogCategoryController::class, 'index']);
Route::get('/blogcategories/{id}', [BlogCategoryController::class, 'show']);
Route::get('/blogs', [BlogController::class, 'index']);
Route::get('/blogs/{id}', [BlogController::class, 'show']);
Route::get('/blogs/category/{categoryId}', [BlogController::class, 'indexByCategory']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
Route::get('/provinces', [ProvinceController::class, 'index']);
Route::get('/provinces/{id}', [ProvinceController::class, 'show']);
Route::get('/cities', [CityController::class, 'index']);
Route::get('/cities/{id}', [CityController::class, 'show']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
Route::get('/unauth', [UnauthController::class, 'unauth'])->name('unauth');
Route::put('/products/{id}', [ProductController::class, 'update']);
Route::post('/products', [ProductController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    // Get Information Authenticated User
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // CRUD User
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    // CRUD Blog Categories
    Route::post('/blogcategories/create', [BlogCategoryController::class, 'store']);
    Route::put('/blogcategories/{id}', [BlogCategoryController::class, 'update']);
    Route::delete('/blogcategories/{id}', [BlogCategoryController::class, 'destroy']);

    // CRUD Blogs
    Route::post('/blogs', [BlogController::class, 'store']);
    Route::patch('/blogs/update/{id}', [BlogController::class, 'update']);
    Route::delete('/blogs/{id}', [BlogController::class, 'destroy']);

    Route::post('/categories/create', [CategoryController::class, 'store']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

    Route::post('/provinces', [ProvinceController::class, 'store']);
    Route::put('/provinces/{id}', [ProvinceController::class, 'update']);
    Route::delete('/provinces/{id}', [ProvinceController::class, 'destroy']);

    Route::post('/cities/create', [CityController::class, 'store']);
    Route::put('/cities/{id}', [CityController::class, 'update']);
    Route::delete('/cities/{id}', [CityController::class, 'destroy']);

    // Route::post('/products', [ProductController::class, 'store']);
    // Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Symlink routes
Route::get('/images/category/{imageName}', [SymlinkController::class, 'categoryImage']);

Route::get('/images/blog/{imageName}', [SymlinkController::class, 'blogImage']);

Route::get('/images/product/{imageName}', [SymlinkController::class, 'productImage']);

Route::get('/images/supplier/{imageName}', [SymlinkController::class, 'supplierImage']);
