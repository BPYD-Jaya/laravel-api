<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BlogCategoryController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\UnauthController;

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