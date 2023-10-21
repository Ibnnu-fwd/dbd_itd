<?php

namespace App\Http\Controllers\Api;

use App\Models\Ksh;
use App\Models\District;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Abj;
use App\Models\DetailKsh;
use App\Models\Regency;

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

    // public function store(Request $request)
    // {
    //     // Menerima data dari permintaan
    //     $data = $request->all();

    //     // Membuat entri baru dalam model "DetailKsh" menggunakan atribut-atribut yang telah Anda tentukan dalam $fillable
    //     $detailKsh = new DetailKsh();
    //     $detailKsh->fill($data); // Mengisi model dengan data dari permintaan

    //     // Menyimpan model "DetailKsh" ke dalam database
    //     $detailKsh->save();

    //     // Mengembalikan respons JSON dengan status 200
    //     return response()->json(200);
    // }

    public function store(Request $request)
    {
        // Data dummy
        $data = [
            'ksh_id' => 1, // ID "Ksh" yang sesuai
            'house_name' => 'rumah a',
            'house_owner' => 'rumah a',
            'latitude' => '-7.276153', // Koordinat latitude
            'longitude' => '112.788692', // Koordinat longitude
            'tpa_type_id' => 1, // ID jenis TPA yang sesuai
            'larva_status' => 1, // Status larva
            'created_by' => 1, // ID pengguna yang membuat entri
            'updated_by' => 1, // ID pengguna yang memperbarui entri
            'is_active' => 1, // Status aktif
            'tpa_description' => 'bakmandi'
        ];
        $datakecamatan = [
            'latitude' => '-7.276153', // Koordinat latitude
            'longitude' => '112.788692', // Koordinat longitude
            'regency_id' => 3578,
            'district_id' => 3578090,
            'village_id' => 3578090006,
            'created_by' => 1,
            'updated_by',
            'is_active' => 1,
        ];
        $dataabj = [
            'district_id' => 3578090,
            'village_id' => 3578090006,
            'ksh_id' => 1,
            'abj_total' => 10,
            'created_by'=> 1,
            'updated_by',
            'is_active'=> 1
        ];
        // Membuat entri baru dalam model "DetailKsh" menggunakan atribut-atribut dari data dummy
        $detailKsh = new DetailKsh();
        $detailKsh->fill($data); // Mengisi model dengan data dummy

        // Menyimpan model "DetailKsh" ke dalam database
        $detailKsh->save();

        $ksh = new Ksh();
        $ksh->fill($datakecamatan);

        $ksh->save();
        $abj = new Abj();
        $abj->fill($dataabj);

        $abj->save();
        // Mengembalikan respons JSON dengan status 200
        return response()->json(200);
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
