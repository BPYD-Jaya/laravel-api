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
        $query = Product::query();

        // Filter by category_id
        if ($request->has('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // Filter by province_id
        if ($request->has('province_id')) {
            $query->where('province_id', $request->input('province_id'));
        }

        // Filter by city_id
        if ($request->has('city_id')) {
            $query->where('city_id', $request->input('city_id'));
        }

        $products = $query->get();

        // Tambahkan image URL untuk setiap produk
        $products = $products->map(function ($product) {
            $product['image_url'] = $this->getImageUrl($product->item_image);
            return $product;
        });

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

    // public function store(Request $request)
    // {
    //     try {
    //         $additionalInfo = $request->input('additional_info', []);

    //         if(count($additionalInfo) > 0) {
    //             $additionalInfo = json_encode($additionalInfo);
    //         } else {
    //             $additionalInfo = null;
    //         }

    //         $newAdditionalInfo = json_decode($additionalInfo, true);
            
    //         $request->validate([
    //             'brand' => 'required',
    //             'product_name' => 'required',
    //             'price' => 'required',
    //             'stock' => 'required',
    //             'volume' => 'required',
    //             'category_id' => 'required',
    //             'description' => 'required',
    //             'province_id' => 'required',
    //             'city_id' => 'required',
    //             'company_name' => 'required',
    //             'company_category' => 'required',
    //             'company_whatsapp_number' => 'required',
    //             'address' => 'required',
    //             'item_image' => 'required',
    //             'storage_type' => 'required',
    //             'packaging' => 'required',
    //             'additional_info' => 'nullable'
    //         ]);

    //         // dd($data);

    //         $product = Product::create([
    //             'brand' => $request->brand,
    //             'product_name' => $request->product_name,
    //             'price' => $request->price,
    //             'stock' => $request->stock,
    //             'volume' => $request->volume,
    //             'category_id' => $request->category_id,
    //             'description' => $request->description,
    //             'province_id' => $request->province_id,
    //             'city_id' => $request->city_id,
    //             'company_name' => $request->company_name,
    //             'company_category' => $request->company_category,
    //             'company_whatsapp_number' => $request->company_whatsapp_number,
    //             'address' => $request->address,
    //             'item_image' => $request->item_image,
    //             'storage_type' => $request->storage_type,
    //             'packaging' => $request->packaging,
    //             'additional_info' => $newAdditionalInfo
    //         ]);

    //         return response()->json([
    //             'status' => 'success',
    //             'data' => $product
    //         ]);
    //     } catch(\Exception $error) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $error->getMessage()
    //         ]);
    //     }
    // }

    public function store(Request $request)
    {
        try {
            $additionalInfo = $request->input('additional_info', []);

            if(count($additionalInfo) > 0) {
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
                'additional_info' => 'nullable'
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
                'additional_info' => $newAdditionalInfo
            ]);

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

    private function getImageUrl($imageName)
    {
        return url("public/product_images/{$imageName}");
    }

    private function uploadImage(Request $request, Product $product)
    {
        $image = $request->file('item_image');
        $imageName = uniqid('product_') . '.' . $image->getClientOriginalExtension();
        $image->storeAs('public/product_images', $imageName);
        $product->update(['item_image' => $imageName]);
    }
}
