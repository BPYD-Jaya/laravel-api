<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function get() {
        try {
            $supplier = Supplier::get();
            
            return response()->json([
                'status' => 'success',
                'data' => $supplier
            ]);
        } catch(\Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => $error->getMessage()
            ]);
        }
    }

    public function getById ($id) {
        try {
            $supplier = Supplier::find($id);
            
            return response()->json([
                'status' => 'success',
                'data' => $supplier
            ]);
        } catch(\Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => $error->getMessage()
            ]);
        }
        
    }

    public function register (Request $request) {
        try {
            $data = [
                'name' => $request->name,
                'company_whatsapp_number' => $request->company_whatsapp_number,
                'company_email' => $request->company_email,
                'company_name' => $request->company_name,
                'company_category' => $request->company_category,
                'brand' => $request->brand,
                'product_name' => $request->product_name,
                'price' => $request->price,
                'stock' => $request->stock,
                'volume' => $request->volume,
                'category_id' => $request->category_id,
                'address' => $request->address,
                'description' => $request->description,
                'province_id' => $request->province_id,
                'city_id' => $request->city_id,
                'item_image' => $request->item_image
            ];

            if($request->hasFile('item_image')) {
                $file = $request->file('item_image');
                $filename = 'product-'.time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/images/products/', $filename);
                $data['item_image'] = $filename;
            }

            $supplier = Supplier::create($data);

            return response()->json([
                'status' => 'success',
                'data' => $supplier
            ]);
        } catch(\Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => $error->getMessage()
            ]);
        }
    }

    public function delete ($id) {
        try {
            $supplier = Supplier::find($id);
            $supplierImage = public_path('storage/images/products/'.$supplier->image);

            if(file_exists($supplierImage)) {
                @unlink($supplierImage);
            }

            $supplier->delete();
            
            return response()->json([
                'status' => 'success',
                'data' => $supplier
            ]);
        } catch(\Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => $error->getMessage()
            ]);
        }
    }

    public function addToProduct($id) {
        try {
            $supplierDetails = Supplier::findOrFail($id);
            
            $product = Product::create([
                'brand' => $supplierDetails->brand,
                'product_name' => $supplierDetails->product_name,
                'price' => $supplierDetails->price,
                'stock' => $supplierDetails->stock,
                'volume' => $supplierDetails->volume,
                'category_id' => $supplierDetails->category_id,
                'description' => $supplierDetails->description,
                'province_id' => $supplierDetails->province_id,
                'city_id' => $supplierDetails->city_id,
                'company_name' => $supplierDetails->company_name,
                'company_category' => $supplierDetails->company_category,
                'address' => $supplierDetails->address,
                'item_image' => $supplierDetails->item_image
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $product
            ]);
        } catch(\Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => $error->getMessage()
            ]);
        }
    }
}
