<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Regency;
use Illuminate\Http\Request;

class RegencyControllerApi extends Controller
{
    public function index(Request $request)
    {
        // Mengambil parameter "page" dari URL atau menggunakan halaman pertama jika tidak ada parameter
        $page = $request->input('page', 1);

        // Jumlah item yang akan ditampilkan dalam setiap halaman
        $perPage = 10;

        // Mengambil data dengan paginasi
        $regencies = Regency::paginate($perPage, ['*'], 'page', $page);

        return response()->json($regencies);
    }

    public function show($id)
    {
        $regency = Regency::find($id);

        if (! $regency) {
            return response()->json(['message' => 'Regency not found'], 404);
        }

        return response()->json($regency);
    }

    public function search(Request $request)
    {
        $filters = $request->all();
        $regencies = Regency::filter($filters)->paginate(10); // Menggunakan paginasi

        return response()->json($regencies);
    }
}
