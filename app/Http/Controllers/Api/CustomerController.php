<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Mail;

class CustomerController extends Controller
{
    public function get() {
        try {
            $customer = Customer::get();
            return response()->json([
                'status' => 'success',
                'data' => $customer
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => $error->getMessage()
            ]);
        }
    }

    public function firstNotification(Request $request) {
        try {
            $data = [
                'email' => $request->email
                
            ];

            $customer = Customer::create($data);

            Mail::to($request->email)->send(new \App\Mail\WelcomeMail());

            return response()->json([
                'status' => 'success',
                'data' => $customer
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => $error->getMessage()
            ]);
        }
        
    }
}
