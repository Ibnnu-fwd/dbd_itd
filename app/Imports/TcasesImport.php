<?php

namespace App\Imports;

use App\Models\TCases;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\District;
use App\Models\Regency;
use App\Models\Village;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class TcasesImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Validasi apakah semua kolom yang diperlukan tersedia
        if (count($row) === 6) {
            // Mengonversi format tanggal Excel ke format 'Y-m-d'
            $date = Date::excelToDateTimeObject($row[0])->format('Y-m-d');

            // Mencari ID distrik berdasarkan nama distrik dari $row[2]
            $districtId = $this->getDistrictId($row[2]);
            $regencyId = $this->getRegencyId($row[1]);
            $villageId = $this->getVillageId($row[3]);
            $vectorType = $row[4];
            $casesTotal = (int) $row[5];

            // Validasi apakah data distrik, kabupaten, dan desa ditemukan atau tidak
            if ($districtId === null || $regencyId === null || $villageId === null) {
                // Anda dapat menangani situasi ini sesuai kebutuhan Anda.
                // Misalnya, lewati baris ini atau berikan pesan kesalahan.
                return null;
            }

            return new TCases([
                'date' => $date,
                'regency_id' => $regencyId,
                'district_id' => $districtId,
                'village_id' => $villageId,
                'vector_type' => $vectorType,
                'cases_total' => $casesTotal,
                'is_active' => true,
            ]);
        } else {
            // Data tidak lengkap, Anda bisa menangani situasi ini sesuai kebutuhan Anda.
            // Misalnya, lewati baris ini atau berikan pesan kesalahan.
            return null;
        }
    }



    // Metode untuk mencari ID distrik berdasarkan nama distrik
    private function getDistrictId($districtName)
    {
        $districtName = strtoupper($districtName);

        $district = District::where('name', $districtName)->first();

        if ($district) {
            return $district->id;
        }
    }
    private function getRegencyId($RegencyName)
    {
        $RegencyName = strtoupper($RegencyName);

        $regency = Regency::where('name', $RegencyName)->first();

        if ($regency) {
            return $regency->id;
        }
    }
    private function getVillageId($VillageName)
    {
        $VillageName = strtoupper($VillageName);

        $village = Village::where('name', $VillageName)->first();

        if ($village) {
            return $village->id;
        }
    }


    // Tentukan nomor baris awal (baris pertama yang akan diimpor)
    public function startRow(): int
    {
        return 2; // Mulai dari baris kedua (lewatkan baris header)
    }
}
