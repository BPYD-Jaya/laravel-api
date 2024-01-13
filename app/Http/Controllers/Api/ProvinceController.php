<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Province;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    public function index()
    {
        $provinces = Province::all();
        return response()->json($provinces);
    }

    public function show($id)
    {
        $province = Province::find($id);

        if (!$province) {
            return response()->json(['message' => 'Province not found'], 404);
        }

        return response()->json($province);
    }

    public function store(Request $request)
    {
        $request->validate([
            'province' => 'required',
        ]);

        $province = Province::create($request->all());

        return response()->json($province, 201);
    }

    public function update(Request $request, $id)
    {
        $province = Province::find($id);

        if (!$province) {
            return response()->json(['message' => 'Province not found'], 404);
        }

        $request->validate([
            'province' => 'required',
        ]);

        $province->update($request->all());

        return response()->json($province, 200);
    }

    public function destroy($id)
    {
        $province = Province::find($id);

        if (!$province) {
            return response()->json(['message' => 'Province not found'], 404);
        }

        $province->delete();

        return response()->json(['message' => 'Province deleted'], 200);
    }
}