<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\District;
use Illuminate\Http\Request;

class DistrictControllerApi extends Controller
{
    public function index(Request $request)
    {
        $page = $request->input('page', 1);

        // Jumlah item yang akan ditampilkan dalam setiap halaman
        $perPage = 10;

        // Mengambil data dengan paginasi
        $districts = District::paginate($perPage, ['*'], 'page', $page);

        return response()->json($districts);
    }

    public function show($id)
    {
        $district = District::find($id);

        if (! $district) {
            return response()->json(['message' => 'District not found'], 404);
        }

        return response()->json($district);
    }

    public function search(Request $request)
    {
        $filters = $request->all();
        $districts = District::filter($filters)->get();

        return response()->json($districts);
    }
}
