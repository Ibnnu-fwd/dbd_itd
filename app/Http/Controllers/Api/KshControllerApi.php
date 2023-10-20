<?php

namespace App\Http\Controllers\Api;

use App\Models\Ksh;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KshControllerApi extends Controller
{
    public function index()
    {
        // Retrieve Ksh data
        $kshData = Ksh::all();

        // Iterate through each Ksh data and calculate total_sample for each one
        foreach ($kshData as $ksh) {
            $totalSample = $ksh->detailKsh->count();
            $ksh->total_sample = $totalSample;
        }

        // Now, you want to add the $abjData transformation code
        $abjData = $kshData->map(function ($item) {
            $item['district'] = $item->district->name; // Change 'name' to the desired column name
            return $item;
        });

        // Return the modified $abjData as JSON response
        return response()->json($abjData, 200);
    }


    public function show($id)
    {
        $ksh = Ksh::find($id);

        if (!$ksh) {
            return response()->json(['message' => 'Ksh not found'], 404);
        }

        return response()->json($ksh, 200);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
            'regency_id' => 'required',
            'district_id' => 'required',
            'village_id' => 'required',
            'created_by' => 'required',
            'updated_by' => 'required',
            'is_active' => 'required',
        ]);

        $ksh = Ksh::create($data);

        return response()->json($ksh, 201);
    }

    public function update(Request $request, $id)
    {
        $ksh = Ksh::find($id);

        if (!$ksh) {
            return response()->json(['message' => 'Ksh not found'], 404);
        }

        $data = $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
            'regency_id' => 'required',
            'district_id' => 'required',
            'village_id' => 'required',
            'created_by' => 'required',
            'updated_by' => 'required',
            'is_active' => 'required',
        ]);

        $ksh->update($data);

        return response()->json($ksh, 200);
    }

    public function destroy($id)
    {
        $ksh = Ksh::find($id);

        if (!$ksh) {
            return response()->json(['message' => 'Ksh not found'], 404);
        }

        $ksh->delete();

        return response()->json(['message' => 'Ksh deleted'], 200);
    }
}
