<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index()
    {
        $cities = City::all();
        return response()->json($cities);
    }

    public function show($id)
    {
        $city = City::find($id);

        if (!$city) {
            return response()->json(['message' => 'City not found'], 404);
        }

        return response()->json($city);
    }

    public function store(Request $request)
    {
        $request->validate([
            'city' => 'required',
            'province_id' => 'required|exists:provinces,id',
        ]);

        $city = City::create($request->all());

        return response()->json($city, 201);
    }

    public function update(Request $request, $id)
    {
        $city = City::find($id);

        if (!$city) {
            return response()->json(['message' => 'City not found'], 404);
        }

        $request->validate([
            'city' => 'required',
            'province_id' => 'required|exists:provinces,id',
        ]);

        $city->update($request->all());

        return response()->json($city, 200);
    }

    public function destroy($id)
    {
        $city = City::find($id);

        if (!$city) {
            return response()->json(['message' => 'City not found'], 404);
        }

        $city->delete();

        return response()->json(['message' => 'City deleted'], 200);
    }
}
