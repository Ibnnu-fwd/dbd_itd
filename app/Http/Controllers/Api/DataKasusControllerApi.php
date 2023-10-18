<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TCases;

class DataKasusControllerApi extends Controller
{
    // Mendapatkan daftar semua kasus
    public function index()
    {
        $cases = TCases::all();
        return response()->json($cases, 200);
    }

    // Mendapatkan detail kasus berdasarkan ID
    public function show($id)
    {
        $case = TCases::find($id);

        if (!$case) {
            return response()->json(['message' => 'Case not found'], 404);
        }

        return response()->json($case, 200);
    }

    // Membuat kasus baru
    public function store(Request $request)
    {
        $data = $request->all();
        $case = TCases::create($data);

        return response()->json($case, 201);
    }

    // Mengupdate kasus berdasarkan ID
    public function update(Request $request, $id)
    {
        $case = TCases::find($id);

        if (!$case) {
            return response()->json(['message' => 'Case not found'], 404);
        }

        $data = $request->all();
        $case->update($data);

        return response()->json($case, 200);
    }

    // Menghapus kasus berdasarkan ID
    public function destroy($id)
    {
        $case = TCases::find($id);

        if (!$case) {
            return response()->json(['message' => 'Case not found'], 404);
        }

        $case->delete();

        return response()->json(['message' => 'Case deleted'], 200);
    }
}
