<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Http\Controllers\Api\NotificationController;
use Mockery\Matcher\Not;

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

            $notifController = new NotificationController();
            $notifController->emailNotification();
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

    public function addToProduct(Request $request) {
        try {
            $additionalInfo = $request->input('additional_info', []);

            if(count($additionalInfo) > 0) {
                $additionalInfo = json_encode($additionalInfo);
            } else {
                $additionalInfo = null;
            }

            $newAdditionalInfo = json_decode($additionalInfo, true);
            
            $data = [
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
                'item_image' => $request->item_image,
                'storage_type' => $request->storage_type,
                'packaging' => $request->packaging,
                'additional_info' => $newAdditionalInfo
            ];

            // dd($data);

            $product = Product::create($data);

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
