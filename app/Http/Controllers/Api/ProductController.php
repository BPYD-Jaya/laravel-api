<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        try {
            $perPage = $request->query('per_page', 10);
            $category_id = $request->query('category_id');
            $city_id = $request->query('city_id');
            $province_id = $request->query('province_id');
            $search = $request->query('search');

            $productsQuery = Product::query();

            if ($category_id) {
                $productsQuery->where('category_id', $category_id);
            }

            if ($city_id) {
                $productsQuery->where('city_id', $city_id);
            }

            if ($province_id) {
                $productsQuery->where('province_id', $province_id);
            }

            if ($search) {
                $productsQuery->where(function ($query) use ($search) {
                    $query->where('brand', 'like', '%' . $search . '%')
                        ->orWhere('product_name', 'like', '%' . $search . '%');
                    // You can extend this to include other fields in your search
                });
            }

            $products = $productsQuery->paginate($perPage);

            $waLink = About::pluck('wa_link')->first();

            foreach ($products as $product) {
                $product->link_image = $this->getImageUrl($product->item_image);
                $text = "Halo,+saya+ingin+membeli+produk+" . $product->brand . ".+Apakah+masih+tersedia?";
                $product->wa_link = $waLink . "?text=" . $text;
            }

            return response()->json([
                'status' => 'success',
                'data' => $products
            ], 200);
        } catch (\Exception $error) {
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
    }

    public function destroy($id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json(['message' => 'Product not found'], 404);
            }

            // Delete product image
            Storage::delete('public/images/products/' . $product->item_image);

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
        return asset("storage/images/products/{$imageName}");
    }
}

