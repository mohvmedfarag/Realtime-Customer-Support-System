<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\OilVolume;
use Illuminate\Http\Request;

class OilVolumeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $volumes = OilVolume::all();
        return response()->json($volumes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'volume' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'oil_viscosity_id' => 'required|exists:oil_viscosities,id',
        ]);

        $volume = OilVolume::create([
            'volume' => $request->input('volume'),
            'price' => $request->input('price'),
            'oil_viscosity_id' => $request->input('oil_viscosity_id'),
        ]);

        return response()->json([
            'message' => 'Oil volume created successfully',
            'volume' => $volume,
        ], 201);
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
