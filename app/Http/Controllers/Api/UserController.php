<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function notification() {
        try {
            Auth::user()->unreadNotifications->markAsRead();
            return response()->json([
                'status' => 'success',
                'data' => Auth::user()->notifications
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => $error->getMessage()
            ]);
        }
    }
}
