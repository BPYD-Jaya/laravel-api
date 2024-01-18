<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class UnauthController extends Controller
{
    public function unauth()
    {
        return Response::json(['error' => 'Unauthorized - Please Login'], 401);
    }
}
