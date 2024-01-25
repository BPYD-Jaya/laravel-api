<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        // Tambahkan symlink untuk setiap gambar kategori
        $categories = $categories->map(function ($category) {
            $category['image_url'] = $this->getImageUrl($category->category_image);
            return $category;
        });

        return response()->json($categories);
    }

    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        // Tambahkan symlink untuk gambar kategori
        $category['image_url'] = $this->getImageUrl($category->category_image);

        return response()->json($category);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required',
            'category_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,heic|max:2048',
        ]);

        try {
            $category = Category::create($request->all());

            // Handle upload gambar kategori jika diberikan
            if ($request->hasFile('category_image')) {
                $this->uploadImage($request, $category);
            }

            return response()->json([
                'category' => $category,
                'message' => 'Category created successfully',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return response()->json(['message' => 'Category not found'], 404);
            }

            $request->validate([
                'category' => 'nullable',
                'category_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,heic|max:2048',
            ]);

            $updateDetails = [];

            // Perbarui field 'category' jika diberikan
            if ($request->filled('category')) {
                $updateDetails['category'] = $request->category;
            }

            // Perbarui gambar kategori jika diberikan
            if ($request->hasFile('category_image')) {
                $this->uploadImage($request, $category);
            }

            if (!empty($updateDetails)) {
                $category->update($updateDetails);
            }

            // Ambil data kategori yang diperbarui
            $updatedCategory = Category::find($id);

            return response()->json([
                'category' => $updatedCategory,
                'message' => 'Category updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        $category = Category::find($id);
    
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
    
        // Hapus gambar kategori
        Storage::delete('public/images/category_images/' . $category->category_image);
    
        $category->delete();
    
        return response()->json(['message' => 'Category deleted'], 200);
    }

    private function getImageUrl($imageName)
    {
        return asset ("/storage/images/category_images/{$imageName}");
    }

    private function uploadImage(Request $request, Category $category)
    {
        $image = $request->file('category_image');
        $imageName = 'category_' . $category->id . '.' . $image->getClientOriginalExtension();

        // Simpan gambar ke dalam folder storage terlebih dahulu
        $path = $image->storeAs('public/images/category_images', $imageName);

        // Update nama gambar ke database
        $category->update(['category_image' => basename($path)]);
    }
}
