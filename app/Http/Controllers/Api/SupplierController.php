<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
            $supplier = Supplier::where('id', $id)->get();
            
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
                'phone' => $request->phone,
                'email' => $request->email,
                'company_name' => $request->company_name,
                'product' => $request->product,
                'price' => $request->price,
                'stock' => $request->stock,
                'unit' => $request->unit,
                'category_id' => $request->category_id,
                'location' => $request->location,
                'image' => $request->image,
                'description' => $request->description
            ];

            if($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = 'supplier-'.time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/images/supplier', $filename);
                $data['image'] = $filename;
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
            $supplierImage = public_path('storage/images/supplier/'.$supplier->image);

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
}
