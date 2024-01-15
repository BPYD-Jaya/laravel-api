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

        // Add symlink for each category image
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

        // Add symlink for the category image
        $category['image_url'] = $this->getImageUrl($category->category_image);

        return response()->json($category);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required',
            'category_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,heic|max:2048',
        ]);

        $category = Category::create($request->all());

        // Handle category image upload if provided
        if ($request->hasFile('category_image')) {
            $this->uploadImage($request, $category);
        }

        return response()->json($category, 201);
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $request->validate([
            'category' => 'required',
            'category_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,heic|max:2048',
        ]);

        // Check if the request has a new image
        if ($request->hasFile('category_image')) {
            // Delete old image
            Storage::delete('category_images/' . $category->category_image);
            // Upload new image and update category data
            $this->uploadImage($request, $category);
        } else {
            // If no new image, update only the category data
            $category->update([
                'category' => $request->input('category'),
            ]);
        }

        // Add symlink for the category image
        $category['image_url'] = $this->getImageUrl($category->category_image);

        return response()->json(['category' => $category, 'message' => 'Category updated successfully']);
    }

    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        // Delete category image
        Storage::delete('category_images/' . $category->category_image);

        $category->delete();

        return response()->json(['message' => 'Category deleted'], 200);
    }

    private function getImageUrl($imageName)
    {
        return url("category_images/{$imageName}");
    }

    private function uploadImage(Request $request, Category $category)
    {
        $image = $request->file('category_image');
        $imageName = 'category_' . $category->id . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('category_images'), $imageName);
        $category->update(['category_image' => $imageName]);
    }
}
