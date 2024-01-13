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
        return response()->json($categories);
    }

    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

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
            $image = $request->file('category_image');
            $imageName = 'category_' . $category->id . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('category_images'), $imageName);
            $category->update(['category_image' => $imageName]);
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

        $category->update($request->all());

        // Handle category image update if provided
        if ($request->hasFile('category_image')) {
            // Delete the old image
            Storage::delete('category_images/' . $category->category_image);

            // Upload and update with the new image
            $image = $request->file('category_image');
            $imageName = 'category_' . $category->id . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('category_images'), $imageName);
            $category->update(['category_image' => $imageName]);
        }

        return response()->json($category, 200);
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
}
