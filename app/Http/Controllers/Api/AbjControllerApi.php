<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Abj;

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
}
