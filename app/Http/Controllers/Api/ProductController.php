<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }

    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json($product);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'nullable',
            'price' => 'required',
            'stock' => 'required',
            'unit' => 'required',
            'item_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,heic|max:2048',
            'description' => 'nullable',
            'category_id' => 'required|exists:categories,id',
            'province_id' => 'required|exists:provinces,id',
            'city_id' => 'required|exists:cities,id',
        ]);
    
        $imageName = uniqid('product_') . '.' . $request->file('item_image')->getClientOriginalExtension();
    
        $product = Product::create([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'stock' => $request->input('stock'),
            'unit' => $request->input('unit'),
            'item_image' => $imageName,
            'description' => $request->input('description'),
            'category_id' => $request->input('category_id'),
            'province_id' => $request->input('province_id'),
            'city_id' => $request->input('city_id'),
        ]);
    
        $request->file('item_image')->storeAs('public/product_images', $imageName);
    
        return response()->json($product, 201);
    }


    public function update(Request $request, $id)
    {
        $product = Product::find($id);
    
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
    
        $request->validate([
            'name' => 'nullable',
            'price' => 'required',
            'stock' => 'required',
            'unit' => 'required',
            'item_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,heic|max:2048',
            'description' => 'nullable',
            'category_id' => 'required|exists:categories,id',
            'province_id' => 'required|exists:provinces,id',
            'city_id' => 'required|exists:cities,id',
        ]);
    
        // Simpan item_image lama untuk referensi
        $oldItemImage = $product->item_image;
    
        // Update produk dengan parameter yang diberikan
        $product->update($request->only([
            'name', 'price', 'stock', 'unit', 'description',
            'category_id', 'province_id', 'city_id',
        ]));
    
        // Handle item image update if provided
        if ($request->hasFile('item_image')) {
            // Delete the old image
            Storage::delete('public/product_images/' . $oldItemImage);
    
            // Upload and update with the new image
            $imageName = uniqid('product_') . '.' . $request->file('item_image')->getClientOriginalExtension();
            $request->file('item_image')->storeAs('public/product_images', $imageName);
            $product->update(['item_image' => $imageName]);
        }
    
        return response()->json($product, 200);
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Delete product image
        Storage::delete('product_images/' . $product->item_image);

        $product->delete();

        return response()->json(['message' => 'Product deleted'], 200);
    }
}
