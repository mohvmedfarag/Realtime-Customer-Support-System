<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\OilViscosity;
use Illuminate\Http\Request;

class OilViscosityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $viscosities = OilViscosity::all();
        return response()->json([
            'status' => true,
            'viscosities' => $viscosities,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'grade' => 'required|string',
            'oil_brand_id' => 'required|exists:oil_brands,id',
            'price_range' => 'string',
        ]);

        $viscosity = OilViscosity::create([
            'grade' => $request->grade,
            'oil_brand_id' => $request->oil_brand_id,
            'price_range' => $request->price_range,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Viscosity Created Successfully',
            'viscosity' => $viscosity,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
