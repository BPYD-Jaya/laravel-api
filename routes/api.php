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
use App\Http\Controllers\Api\UnauthController;




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
    });

    Route::prefix("/notification")->group(function(){
        Route::post("/whatsapp", [App\Http\Controllers\Api\NotificationController::class, 'whatsappNotification']);
        Route::get("/email", [App\Http\Controllers\Api\NotificationController::class, 'emailNotification']);
    });

    Route::prefix('/products')->group(function(){
        Route::post('/', [App\Http\Controllers\Api\SupplierController::class, 'addToProduct']);
    });
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

Route::post('/login', [AuthController::class, 'login']);
Route::get('/blogcategories', [BlogCategoryController::class, 'index']);
Route::get('/blogcategories/{id}', [BlogCategoryController::class, 'show']);
Route::get('/blogs', [BlogController::class, 'index']);
Route::get('/blogs/{id}', [BlogController::class, 'show']);
Route::get('/blogs/category/{categoryId}', [BlogController::class, 'indexByCategory']);

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
    Route::post('/blogcategories', [BlogCategoryController::class, 'store']);
    Route::put('/blogcategories/{id}', [BlogCategoryController::class, 'update']);
    Route::delete('/blogcategories/{id}', [BlogCategoryController::class, 'destroy']);

    // CRUD Blogs
    Route::post('/blogs', [BlogController::class, 'store']);
    Route::put('/blogs/{id}', [BlogController::class, 'update']);
    Route::delete('/blogs/{id}', [BlogController::class, 'destroy']);

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Contoh Symlink Blog
Route::get('/images/blog/{imageName}', function ($imageName) {
    $imagePath = public_path('images/blog/' . $imageName);

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

Route::get('/unauth', [UnauthController::class, 'unauth'])->name('unauth');