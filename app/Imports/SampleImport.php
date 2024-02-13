<?php

namespace App\Imports;

use App\Models\DetailSampleMorphotype;
use App\Models\DetailSampleSerotype;
use App\Models\DetailSampleVirus;
use App\Models\District;
use App\Models\LocationType;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Sample;
// use App\Models\SampleMethod;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SampleImport implements ToModel, WithChunkReading, WithMultipleSheets, WithStartRow, WithValidation
{
    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function startRow(): int
    {
        return 3;
    }

    public $fileCode;

    public function __construct($fileCode)
    {
        $this->fileCode = $fileCode;
    }

    public function rules(): array
    {
        return [
            '*.0' => 'required',
            '*.1' => 'required',
            '*.2' => 'required',
            '*.3' => 'required',
            '*.4' => 'required',
            '*.5' => 'required',
            '*.6' => 'required',
            '*.7' => 'required',
            '*.8' => 'required',
            '*.9' => 'nullable',
            '*.10' => 'nullable',
            '*.11' => 'nullable',
            '*.12' => 'nullable',
            '*.13' => 'nullable',
            '*.14' => 'nullable',
            '*.15' => 'nullable',
            '*.16' => 'nullable',
            '*.17' => 'nullable',
            '*.18' => 'nullable',
            '*.19' => 'nullable',
            '*.20' => 'nullable',
            '*.21' => 'nullable',
            '*.22' => 'nullable',
            '*.23' => 'nullable',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.0.required' => 'Tanggal pengambilan sampel tidak boleh kosong',
            '*.1.required' => 'Provinsi tidak boleh kosong',
            '*.2.required' => 'Kabupaten/Kota tidak boleh kosong',
            '*.3.required' => 'Kecamatan tidak boleh kosong',
            '*.4.required' => 'Desa tidak boleh kosong',
            '*.5.required' => 'Tipe lokasi tidak boleh kosong',
            '*.6.required' => 'Nama lokasi tidak boleh kosong',
            '*.7.required' => 'Nama pukesmas tidak boleh kosong',
            '*.8.required' => 'Latitude tidak boleh kosong',
            '*.9.required' => 'Longitude tidak boleh kosong',
        ];
    }

    public function model(array $row)
    {
        $createdAt = $row[0];
        // Coba membuat objek DateTime
        $dateTime = \DateTime::createFromFormat('n/j/Y', $createdAt);

        // Periksa apakah objek DateTime berhasil dibuat
        if ($dateTime !== false) {
            // Objek DateTime berhasil dibuat, maka Anda dapat memanggil format() di sini
            $createdAt = $dateTime->format('Y-m-d'); // Gantilah format sesuai kebutuhan Anda
            echo $createdAt;
        } else {
            // Penanganan kesalahan jika konversi gagal
            echo 'Konversi tanggal gagal.';
        }
        $province = $this->province($row[1]);
        $regency = $this->regency($row[2]);
        $district = $this->district($row[3]);
        $village = $this->village($row[4]);
        $locationType = $this->locationType($row[5]);
        $locationName = $row[6];
        $publicHealthName = $row[7];
        $latitude = str_replace(',', '.', $row[8]);
        $longitude = str_replace(',', '.', $row[9]);
        $sampleCode = $this->generateSampleCode();
        $sample = Sample::where('sample_code', $sampleCode)->first();

        $aedesAegypti = (int) $row[10] ?? 0;
        $aedesAlbopictus = (int) $row[11] ?? 0;
        $culex = (int) $row[12] ?? 0;

        $morphotype1 = (int) $row[13] ?? 0;
        $morphotype2 = (int) $row[14] ?? 0;
        $morphotype3 = (int) $row[15] ?? 0;
        $morphotype4 = (int) $row[16] ?? 0;
        $morphotype5 = (int) $row[17] ?? 0;
        $morphotype6 = (int) $row[18] ?? 0;
        $morphotype7 = (int) $row[19] ?? 0;

        $unidentified = 0;
        if ($aedesAegypti != 0) {
            // check morphotype there's no value, then unidentified = aedesAegypti
            if ($morphotype1 == 0 && $morphotype2 == 0 && $morphotype3 == 0 && $morphotype4 == 0 && $morphotype5 == 0 && $morphotype6 == 0 && $morphotype7 == 0) {
                $unidentified = $aedesAegypti;
            } elseif ($morphotype1 != 0 || $morphotype2 != 0 || $morphotype3 != 0 || $morphotype4 != 0 || $morphotype5 != 0 || $morphotype6 != 0 || $morphotype7 != 0) {
                $unidentified = $aedesAegypti - ($morphotype1 + $morphotype2 + $morphotype3 + $morphotype4 + $morphotype5 + $morphotype6 + $morphotype7);
            }

            // if unidentified < 0, then unidentified = 0
            if ($unidentified < 0) {
                $unidentified = 0;
            }
        }

        $denv1 = $row[20] == '' ? 0 : 1;
        $denv2 = $row[21] == '' ? 0 : 1;
        $denv3 = $row[22] == '' ? 0 : 1;
        $denv4 = $row[23] == '' ? 0 : 1;

        if ($sample) {
            $sampleCode = $this->generateSampleCode();
        }

        // dd($aedesAegypti, $aedesAlbopictus, $culex, $morphotype1, $morphotype2, $morphotype3, $morphotype4, $morphotype5, $morphotype6, $morphotype7, $unidentified, $denv1, $denv2, $denv3, $denv4);

        if ($createdAt == null || $province == null || $regency == null || $district == null || $village == null || $locationType == null || $locationName == null || $publicHealthName == null || $latitude == null || $longitude == null) {
            return null;
        } else {
            $sample = Sample::create([
                'sample_code' => $sampleCode,
                'file_code' => $this->fileCode,
                'created_at' => $createdAt,
                'province_id' => $province,
                'regency_id' => $regency,
                'district_id' => $district,
                'village_id' => $village,
                'location_type_id' => $locationType,
                'location_name' => $locationName,
                'public_health_name' => $publicHealthName,
                'latitude' => $latitude,
                'longitude' => $longitude,
            ]);

            // insert aedes albopictus
            if ($aedesAlbopictus != 0) {
                DetailSampleVirus::create([
                    'sample_id' => $sample->id,
                    'virus_id' => 2,
                    'amount' => $aedesAlbopictus,
                ]);
            }

            // insert culex
            if ($culex != 0) {
                DetailSampleVirus::create([
                    'sample_id' => $sample->id,
                    'virus_id' => 3,
                    'amount' => $culex,
                ]);
            }

            if ($aedesAegypti != 0) {
                $detailSampleVirus = DetailSampleVirus::create([
                    'sample_id' => $sample->id,
                    'virus_id' => 1,
                    'identification' => 1,
                ]);
                if ($unidentified != 0) {
                    DetailSampleMorphotype::create([
                        'detail_sample_virus_id' => $detailSampleVirus->id,
                        'morphotype_id' => 8,
                        'amount' => $unidentified,
                    ]);
                } else {
                    DetailSampleMorphotype::create([
                        'detail_sample_virus_id' => $detailSampleVirus->id,
                        'morphotype_id' => 8,
                        'amount' => 0,
                    ]);
                }

                DetailSampleMorphotype::create([
                    'detail_sample_virus_id' => $detailSampleVirus->id,
                    'morphotype_id' => 1,
                    'amount' => $morphotype1,
                ]);

                DetailSampleMorphotype::create([
                    'detail_sample_virus_id' => $detailSampleVirus->id,
                    'morphotype_id' => 2,
                    'amount' => $morphotype2,
                ]);

                DetailSampleMorphotype::create([
                    'detail_sample_virus_id' => $detailSampleVirus->id,
                    'morphotype_id' => 3,
                    'amount' => $morphotype3,
                ]);

                DetailSampleMorphotype::create([
                    'detail_sample_virus_id' => $detailSampleVirus->id,
                    'morphotype_id' => 4,
                    'amount' => $morphotype4,
                ]);

                DetailSampleMorphotype::create([
                    'detail_sample_virus_id' => $detailSampleVirus->id,
                    'morphotype_id' => 5,
                    'amount' => $morphotype5,
                ]);

                DetailSampleMorphotype::create([
                    'detail_sample_virus_id' => $detailSampleVirus->id,
                    'morphotype_id' => 6,
                    'amount' => $morphotype6,
                ]);

                DetailSampleMorphotype::create([
                    'detail_sample_virus_id' => $detailSampleVirus->id,
                    'morphotype_id' => 7,
                    'amount' => $morphotype7,
                ]);
            }
        }

        if ($denv1 != 0) {
            DetailSampleSerotype::create([
                'sample_id' => $sample->id,
                'serotype_id' => 1,
                'status' => $denv1,
            ]);

            DetailSampleSerotype::create([
                'sample_id' => $sample->id,
                'serotype_id' => 2,
                'status' => $denv2,
            ]);

            DetailSampleSerotype::create([
                'sample_id' => $sample->id,
                'serotype_id' => 3,
                'status' => $denv3,
            ]);

            DetailSampleSerotype::create([
                'sample_id' => $sample->id,
                'serotype_id' => 4,
                'status' => $denv4,
            ]);
        }
    }

    public function generateSampleCode()
    {
        $lastSample = Sample::orderBy('id', 'desc')->first();
        $lastId = $lastSample ? $lastSample->id : 0;
        $year = date('Y');
        $code = 'SC-'.$year.'-'.sprintf('%04s', $lastId + 1);

        return $code;
    }

    public function province($province)
    {
        $province = strtoupper($province);
        $province = Province::where('name', 'like', '%'.$province.'%')->first();

        return $province->id;
    }

    public function regency($regency)
    {
        $regency = strtoupper($regency);
        $regency = Regency::where('name', 'like', '%'.$regency.'%')->first();

        return $regency->id ?? null;
    }

    public function district($param)
    {
        $param = strtoupper($param);
        $district = District::where('name', 'like', '%'.$param.'%')->first();

        return $district->id ?? null;
    }

    public function village($param)
    {
        $param = strtoupper($param);
        $village = DB::table('villages')->where('name', $param)->cursor();

        return $village->first()->id;
    }

    // public function sampleMethodId($param)
    // {
    //     $sampleMethodId = SampleMethod::where('name', 'like', '%' . $param . '%')->first();
    //     if ($sampleMethodId == null) {
    //         $sampleMethodId = SampleMethod::create([
    //             'name' => ucwords($param)
    //         ]);
    //         return $sampleMethodId->id;
    //     }

    //     return $sampleMethodId->id;
    // }

    public function locationType($param)
    {
        $param = strtoupper($param);
        $locationType = LocationType::where('name', 'like', '%'.$param.'%')->first();
        if ($locationType == null) {
            $locationType = LocationType::create([
                'name' => $param,
            ]);

            return $locationType->id;
        }

        return $locationType->id;
    }
}
