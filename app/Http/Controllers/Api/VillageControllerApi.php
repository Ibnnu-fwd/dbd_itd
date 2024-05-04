<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Village;
use Illuminate\Http\Request;

class VillageControllerApi extends Controller
{
    public function show($id)
    {
        // Find the district based on the regency_id
        $village = Village::where('district_id', $id)->get();

        if (!$village) {
            return response()->json(['message' => 'Village not found'], 404);
        }

        return response()->json($village);
    }
}