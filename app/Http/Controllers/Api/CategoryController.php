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
        try {
            $category = Category::find($id);
    
            if (!$category) {
                return response()->json(['message' => 'Category not found'], 404);
            }
    
            $updateDetails = [];
    
            // Update 'category' field if provided
            if ($request->has('category')) {
                $updateDetails['category'] = $request->category;
            }
            echo $request->input('category');
    
// Update category image if provided
if ($request->hasFile('category_image')) {
    // Delete old image if it exists
    if ($category->category_image) {
        Storage::delete('category_images/' . $category->category_image);
    }

    // Upload new image
    $imageName = 'category_' . $category->id . '.' . $request->file('category_image')->getClientOriginalExtension();
    $request->file('category_image')->move(public_path('category_images'), $imageName);
    $updateDetails['category_image'] = $imageName;
} elseif ($request->has('category_image')) {
    // Update category image if it is not provided as a file
    // Delete old image if it exists
    if ($category->category_image) {
        Storage::delete('category_images/' . $category->category_image);
    }
    $updateDetails['category_image'] = $request->input('category_image');
}

    
            if (!empty($updateDetails)) {
                $category->update($updateDetails);
            }
    
            // Fetch the updated category data
            $updatedCategory = Category::find($id);
    
            return response()->json([
                'category' => $category,
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
