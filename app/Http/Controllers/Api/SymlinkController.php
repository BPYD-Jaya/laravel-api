<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;

class SymlinkController extends Controller
{
    public function productImage($imageName) {
        $imagePath = storage_path('app/public/images/products/' . $imageName);
    
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
    }

    public function supplierImage($imageName) {
        $imagePath = storage_path('app/public/images/products/' . $imageName);

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
    }

    public function blogImage($imageName) {
        $imagePath = storage_path('app/public/images/blogs/' . $imageName);
    
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
    }

    public function categoryImage($imageName) {
        $imagePath = storage_path('app/public/images/products/' . $imageName);
    
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
    }
}
