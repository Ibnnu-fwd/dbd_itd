<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Abj;
use App\Models\DetailKsh;
use App\Models\Ksh;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        return response()->json(['locations' => $abjData], 200);
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
        $validatedData = $request->validate([
            'regency_id' => ['required'],
            'district_id' => ['required'],
            'village_id' => ['required'],
            'latitude' => ['required'],
            'longitude' => ['required'],
            'house_name' => ['required'],
            'house_owner' => ['required'],
            'tpa_type_id' => ['required'],
            'larva_status' => ['required'],
            'tpa_description' => ['required'],
        ]);

        // Cek apakah Ksh dengan district_id yang sama sudah ada
        $ksh = Ksh::where('village_id', $validatedData['village_id'])->first();

        if ($ksh) {
            // Jika Ksh sudah ada, tambahkan DetailKsh ke Ksh yang sudah ada
            $detailKsh = $ksh->detailKsh()->create([
                'house_name' => $validatedData['house_name'],
                'house_owner' => $validatedData['house_owner'],
                'latitude' => $validatedData['latitude'],
                'longitude' => $validatedData['longitude'],
                'tpa_type_id' => $validatedData['tpa_type_id'],
                'larva_status' => $validatedData['larva_status'],
                'created_by' => 1,
                'is_active' => true,
                'tpa_description' => $validatedData['tpa_description'],
            ]);
        } else {
            // Jika Ksh belum ada, buat Ksh baru dan tambahkan DetailKsh ke Ksh baru
            $newKsh = Ksh::create([
                'regency_id' => $validatedData['regency_id'],
                'district_id' => $validatedData['district_id'],
                'village_id' => $validatedData['village_id'],
                'latitude' => $validatedData['latitude'],
                'longitude' => $validatedData['longitude'],
                'created_by' => 1,
            ]);

            if ($newKsh) {
                $detailKsh = $newKsh->detailKsh()->create([
                    'house_name' => $validatedData['house_name'],
                    'house_owner' => $validatedData['house_owner'],
                    'latitude' => $validatedData['latitude'],
                    'longitude' => $validatedData['longitude'],
                    'tpa_type_id' => $validatedData['tpa_type_id'],
                    'larva_status' => $validatedData['larva_status'],
                    'created_by' => 1,
                    'is_active' => true,
                    'tpa_description' => $validatedData['tpa_description'],
                ]);
            }
        }

        // Hitung jumlah DetailKsh untuk Ksh yang terkait
        $countData = DetailKsh::where('ksh_id', $detailKsh->ksh_id)->count();
        $countNegatif = DetailKsh::where('ksh_id', $detailKsh->ksh_id)->where('larva_status', 0)->count();

        // Update atau buat Abj terkait
        if ($countData > 0) {
            $abjTotal = ($countNegatif / $countData) * 100;

            $abj = Abj::updateOrCreate(
                ['ksh_id' => $detailKsh->ksh_id],
                [
                    'regency_id' => $detailKsh->ksh->regency_id ?? null,
                    'district_id' => $detailKsh->ksh->district_id ?? null,
                    'village_id' => $detailKsh->ksh->village_id ?? null,
                    'abj_total' => $abjTotal
                ]
            );
        }

        return response()->json([
            'message' => 'DetailKsh berhasil ditambahkan untuk Ksh yang sudah ada.',
        ], 200);
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
