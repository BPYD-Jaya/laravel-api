<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        try {
            $products = Product::paginate(10);
            $waLink = About::pluck('wa_link')->first();

            
            foreach($products as $product) {
                $product->link_image = $this->getImageUrl($product->item_image);
                $text = "Halo,+saya+ingin+membeli+produk+". $product->brand . ".+Apakah+masih+tersedia?";
                $product->wa_link = $waLink . "?text=" . $text;
            }

            return response()->json([
                'status' => 'success',
                'data' => $products
            ], 200);
        } catch(\Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => $error->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $product = Product::find($id);
            $waLink = About::pluck('wa_link')->first();

            if (!$product) {
                return response()->json(['message' => 'Product not found'], 404);
            }
            
            $product->link_image = $this->getImageUrl($product->item_image);
            $text = "Halo,+saya+ingin+membeli+produk+". $product->brand . ".+Apakah+masih+tersedia?";
            $product->wa_link = $waLink . "?text=" . $text;

            return response()->json([
                'status' => 'success',
                'data' => $product,
            ], 200);
        } catch(\Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => $error->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $additionalInfo = $request->input('additional_info', []);

            if (count($additionalInfo) > 0) {
                $additionalInfo = json_encode($additionalInfo);
            } else {
                $additionalInfo = null;
            }

            $newAdditionalInfo = json_decode($additionalInfo, true);

            $request->validate([
                'brand' => 'required',
                'product_name' => 'required',
                'price' => 'required',
                'stock' => 'required',
                'volume' => 'required',
                'category_id' => 'required',
                'description' => 'required',
                'province_id' => 'required',
                'city_id' => 'required',
                'company_name' => 'required',
                'company_category' => 'required',
                'company_whatsapp_number' => 'required',
                'address' => 'required',
                'item_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,heic|max:2048',
                'storage_type' => 'required',
                'packaging' => 'required',
                'additional_info' => 'nullable',
            ]);

            if($request->hasFile('item_image')) {
                $file = $request->file('item_image');
                $filename = 'product-'.time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/images/products/', $filename);
            }

            $product = Product::create([
                'brand' => $request->brand,
                'product_name' => $request->product_name,
                'price' => $request->price,
                'stock' => $request->stock,
                'volume' => $request->volume,
                'category_id' => $request->category_id,
                'description' => $request->description,
                'province_id' => $request->province_id,
                'city_id' => $request->city_id,
                'company_name' => $request->company_name,
                'company_category' => $request->company_category,
                'company_whatsapp_number' => $request->company_whatsapp_number,
                'address' => $request->address,
                'item_image' => $filename,

                'storage_type' => $request->storage_type,
                'packaging' => $request->packaging,
                'additional_info' => $newAdditionalInfo,
            ]);

            // Membuat direktori jika belum ada
            $imageDirectory = public_path('product_images');
            if (!file_exists($imageDirectory)) {
                mkdir($imageDirectory, 0777, true);
            }

            // Upload gambar
            $request->file('item_image')->storeAs('public/product_images', $imageName);

            return response()->json([
                'status' => 'success',
                'data' => $product,
            ], 201);
        } catch(\Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => $error->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Retrieve the existing product
            $product = Product::findOrFail($id);
    
            // Your existing code to handle additional_info
            $additionalInfo = $request->input('additional_info', []);
    
            if (count($additionalInfo) > 0) {
                $additionalInfo = json_encode($additionalInfo);
            } else {
                $additionalInfo = null;
            }
    
            $newAdditionalInfo = json_decode($additionalInfo, true);
    
            // Your existing validation rules
            $request->validate([
                'brand' => 'nullable',
                'product_name' => 'nullable',
                'price' => 'nullable',
                'stock' => 'nullable',
                'volume' => 'nullable',
                'category_id' => 'nullable',
                'description' => 'nullable',
                'province_id' => 'nullable',
                'city_id' => 'nullable',
                'company_name' => 'nullable',
                'company_category' => 'nullable',
                'company_whatsapp_number' => 'nullable',
                'address' => 'nullable',
                'item_image' => 'nullable',
                'storage_type' => 'nullable',
                'packaging' => 'nullable',
                'additional_info' => 'nullable'
            ]);
    
            $oldImage = $product->item_image;
            
            if($request->hasFile('item_image')) {
                Storage::delete('public/images/products/' . $oldImage);
                $file = $request->file('item_image');
                $filename = 'product-'.time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/images/products/', $filename);
                $product->update($request->all());
                $product->update(['item_image' => $filename]);
            }
            
            return response()->json([
                'status' => 'success',
                'data' => $product
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => $error->getMessage()
            ], 500);
        }
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $request->validate([
            'brand' => 'nullable',
            'product_name' => 'nullable',
            'price' => 'nullable',
            'stock' => 'nullable',
            'volume' => 'nullable',
            'category_id' => 'nullable',
            'description' => 'nullable',
            'province_id' => 'nullable',
            'city_id' => 'nullable',
            'company_name' => 'nullable',
            'company_category' => 'nullable',
            'company_whatsapp_number' => 'nullable',
            'address' => 'nullable',
            'item_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,heic|max:2048',
            'storage_type' => 'nullable',
            'packaging' => 'nullable',
            'additional_info' => 'nullable',
        ]);

        // Simpan item_image lama untuk referensi
        $oldItemImage = $product->item_image;

        // Update produk dengan parameter yang diberikan
        $product->update($request->all());

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
        try {
            $product = Product::find($id);
    
            if (!$product) {
                return response()->json(['message' => 'Product not found'], 404);
            }
    
            // Delete product image
            Storage::delete('product_images/' . $product->item_image);
    
            $product->delete();
    
            return response()->json(['message' => 'Product deleted'], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => $error->getMessage()
            ], 500);
        }
    }
    private function getImageUrl($imageName)
    {
        $baseUrl = config('app.url');
        return "{$baseUrl}/api/images/supplier/{$imageName}";

    }
}