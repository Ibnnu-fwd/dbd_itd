<?php

namespace App\Repositories;

use App\Models\DetailSampleVirus;
use App\Models\District;
use App\Models\Morphotype;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Sample;
use App\Models\SampleMethod;
use App\Models\Village;
use App\Models\Virus;
use App\Repositories\Interface\SampleInterface;
use Illuminate\Support\Facades\DB;

class SampleRepository implements SampleInterface
{
    private $sample;
    private $sampleMethod;
    private $province;
    private $regency;
    private $district;
    private $village;
    private $detailSampleVirus;

    public function __construct(
        Sample $sample,
        SampleMethod $sampleMethod,
        Province $province,
        Regency $regency,
        District $district,
        Village $village,
        DetailSampleVirus $detailSampleVirus
    ) {
        $this->sample = $sample;
        $this->sampleMethod = $sampleMethod;
        $this->province = $province;
        $this->regency = $regency;
        $this->district = $district;
        $this->village = $village;
        $this->detailSampleVirus = $detailSampleVirus;
    }

    public function getAll()
    {
        return $this->sample->with('sampleMethod', 'province', 'regency', 'district', 'village', 'createdBy', 'updatedBy')->active()->get();
    }

    public function getById($id)
    {
        return $this->sample->with('sampleMethod', 'province', 'regency', 'district', 'village')->active()->find($id);
    }

    public function create(array $attributes)
    {
        DB::beginTransaction();
        try {
            $sample = $this->sample->create([
                'sample_code' => $this->sample->generateSampleCode(),
                'sample_method_id' => $attributes['sample_method_id'],
                'latitude' => $attributes['latitude'],
                'longitude' => $attributes['longitude'],
                'province_id' => $attributes['province_id'],
                'regency_id' => $attributes['regency_id'],
                'district_id' => $attributes['district_id'],
                'village_id' => $attributes['village_id'],
                'location_name' => $attributes['location_name'] ?? null,
                'location_type_id' => $attributes['location_type_id'] ?? null,
                'description' => $attributes['description'] ?? null,
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        try {
            foreach ($attributes['viruses'] as $virus => $key) {
                $this->detailSampleVirus->create([
                    'sample_id' => $sample->id,
                    'virus_id' => $key,
                ]);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        DB::commit();
    }

    public function update($id, array $attributes)
    {
        DB::beginTransaction();
        try {
            $sample = $this->sample->find($id)->update([
                'sample_method_id' => $attributes['sample_method_id'],
                'location_name' => $attributes['location_name'],
                'location_type_id' => $attributes['location_type_id'],
                'description' => $attributes['description'],
                'province_id' => $attributes['province_id'],
                'regency_id' => $attributes['regency_id'],
                'district_id' => $attributes['district_id'],
                'village_id' => $attributes['village_id'],
                'latitude' => $attributes['latitude'],
                'longitude' => $attributes['longitude'],
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        if (isset($attributes['viruses'])) {
            try {
                foreach ($attributes['viruses'] as $virus => $key) {
                    $this->detailSampleVirus->create([
                        'sample_id' => $id,
                        'virus_id' => $key,
                    ]);
                }
            } catch (\Throwable $th) {
                DB::rollBack();
                throw $th;
            }
        }

        DB::commit();
    }

    public function delete($id)
    {
        return $this->sample->find($id)->update([
            'is_active' => false,
        ]);
    }

    public function detailSample($id)
    {
        return $this->sample->active()->with('detailSampleViruses', 'detailSampleViruses.detailSampleMorphotypes', 'detailSampleViruses.detailSampleMorphotypes.detailSampleSerotypes')->find($id);
    }
}
