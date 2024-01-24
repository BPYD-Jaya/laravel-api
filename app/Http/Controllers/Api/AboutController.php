<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\About;

class AboutController extends Controller
{
    public function get(){
        try{
            $about = About::get();
            return response()->json([
                'status' => 'success',
                'data' => $about
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => $error->getMessage()
            ], 500);
        }
    }

    public function specific($id){
        try{
            $about = About::find($id);
            return response()->json([
                'status' => 'success',
                'data' => $about
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => $error->getMessage()
            ], 500);
        }
    }

    public function create(Request $request){
        try{
            $about = About::create($request->all());
            return response()->json([
                'status' => 'success',
                'data' => $about
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => $error->getMessage()
            ]);
        }
    }

    public function update(Request $request, $id){
        try{
            $about = About::find($id);

            if(!$about) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'about not found'
                ], 404);
            }
            
            $about->update($request->all());

            return response()->json([
                'status' => 'success',
                'data' => $about
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => $error->getMessage()
            ]);
        }
    }
    
}
