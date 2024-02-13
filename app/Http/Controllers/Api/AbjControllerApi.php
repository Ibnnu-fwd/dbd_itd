<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Abj;
use App\Models\District;
use App\Models\Regency;
use Illuminate\Http\Request;

class AbjControllerApi extends Controller
{
    public function index()
    {
        $abjData = Abj::active()->get(); // Mengambil data Abj yang aktif

        // Modifikasi data dengan menambahkan kolom 'district'
        $abjData = $abjData->map(function ($item) {
            $item['district'] = $item->district->name; // Gantilah 'name' sesuai dengan kolom yang ingin Anda tambahkan

            return $item;
        });

        return response()->json($abjData);
    }

    public function search(Request $request)
    {
        $kecamatan = $request->input('kecamatan'); // Menerima data pencarian dari Flutter
        // $kabupaten = $request->input('kabupaten');
        // $regenciesData = Regency::where('name', $kabupaten)->first();
        $districtData = District::where('name', $kecamatan)->first();

        if ($districtData) {
            // District yang sesuai ditemukan
            $abjData = Abj::where('district_id', $districtData->id)->get();

            // Modifikasi data Abj dengan menambahkan kolom 'district'
            $abjData = $abjData->map(function ($item) {
                $item['district'] = $item->district->name; // Gantilah 'name' sesuai dengan kolom yang ingin Anda tambahkan

                return $item;
            });

            return response()->json($abjData);
        } else {
            // District tidak ditemukan
            return response()->json([]);
        }
    }
}
