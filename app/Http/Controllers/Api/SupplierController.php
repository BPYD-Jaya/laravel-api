<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Http\Controllers\Api\NotificationController;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;

class SupplierController extends Controller
{
    public function get(Request $request) {
        try {
            $query = $request->query('company_name');

            $supplier = null;

            if ($query) {
                $supplier = Supplier::select(
                                'suppliers.id',
                                'suppliers.name',
                                'suppliers.company_name',
                                'suppliers.company_whatsapp_number',
                                'suppliers.company_email',
                                'suppliers.company_category',
                                'suppliers.stock',
                                'suppliers.price',
                                'suppliers.volume',
                                'suppliers.address',
                                'suppliers.item_image',
                                'suppliers.description',
                                'cities.city',
                                'provinces.province',
                                'categories.category',
                                'suppliers.created_at',
                                'suppliers.category_id',
                                'suppliers.province_id',
                                'suppliers.city_id'
                            )
                            ->leftJoin('cities', 'suppliers.city_id', '=', 'cities.id')
                            ->leftJoin('provinces', 'suppliers.province_id', '=', 'provinces.id')
                            ->leftJoin('categories', 'suppliers.category_id', '=', 'categories.id')
                            ->where('company_name', 'LIKE', "%{$query}%")
                            ->orderBy('suppliers.created_at', 'desc')
                            ->paginate(10);
            } else {
                $supplier = Supplier::select(
                                'suppliers.id',
                                'suppliers.name',
                                'suppliers.company_name',
                                'suppliers.company_whatsapp_number',
                                'suppliers.company_email',
                                'suppliers.company_category',
                                'suppliers.stock',
                                'suppliers.price',
                                'suppliers.volume',
                                'suppliers.address',
                                'suppliers.item_image',
                                'suppliers.description',
                                'cities.city',
                                'provinces.province',
                                'categories.category',
                                'suppliers.created_at',
                                'suppliers.category_id',
                                'suppliers.province_id',
                                'suppliers.city_id'
                            )
                            ->leftJoin('cities', 'suppliers.city_id', '=', 'cities.id')
                            ->leftJoin('provinces', 'suppliers.province_id', '=', 'provinces.id')
                            ->leftJoin('categories', 'suppliers.category_id', '=', 'categories.id')
                            ->orderBy('suppliers.created_at', 'desc')
                            ->paginate(10);
            }            

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
            $supplier = Supplier::select(
                'suppliers.id',
                'suppliers.name',
                'suppliers.company_name',
                'suppliers.company_whatsapp_number',
                'suppliers.company_email',
                'suppliers.company_category',
                'suppliers.stock',
                'suppliers.price',
                'suppliers.volume',
                'suppliers.address',
                'suppliers.item_image',
                'suppliers.description',
                'cities.city',
                'provinces.province',
                'categories.category',
                'suppliers.created_at',
                'suppliers.category_id',
                'suppliers.province_id',
                'suppliers.city_id'
            )
            ->leftJoin('cities', 'suppliers.city_id', '=', 'cities.id')
            ->leftJoin('provinces', 'suppliers.province_id', '=', 'provinces.id')
            ->leftJoin('categories', 'suppliers.category_id', '=', 'categories.id')
            ->where('suppliers.id', $id)
            ->get();
            
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

            event(new Registered($supplier = Supplier::create($data)));

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

            if($product) {
                Mail::to($request->company_email)->send(new \App\Mail\SupplierMail());
            }

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

    private function getImageUrl($imageName) {
        $baseUrl = config('app.url');
        return url("{$baseUrl}/api/images/products/{$imageName}");
    }
}
