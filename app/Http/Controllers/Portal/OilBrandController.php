<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\OilBrand;
use Illuminate\Http\Request;

class OilBrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = OilBrand::all();
        return response()->json([
            'status' => true,
            'brands' => $brands,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'price_range' => 'string',
        ]);

        $brand = OilBrand::create([
            'name' => $request->name,
            'price_range' => $request->price_range,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Brand Created Successfully',
            'brands' => $brand,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $brand = OilBrand::findOrFail($id);
        return response()->json([
            'status' => true,
            'brands' => $brand,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
