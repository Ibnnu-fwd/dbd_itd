<?php

namespace App\Repositories;

use App\Models\DetailSampleMorphotype;
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
use Carbon\Carbon;
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
    private $detailSampleMorophotype;

    public function __construct(
        Sample $sample,
        SampleMethod $sampleMethod,
        Province $province,
        Regency $regency,
        District $district,
        Village $village,
        DetailSampleVirus $detailSampleVirus,
        DetailSampleMorphotype $detailSampleMorphotype
    ) {
        $this->sample = $sample;
        $this->sampleMethod = $sampleMethod;
        $this->province = $province;
        $this->regency = $regency;
        $this->district = $district;
        $this->village = $village;
        $this->detailSampleVirus = $detailSampleVirus;
        $this->detailSampleMorophotype = $detailSampleMorphotype;
    }

    public function getAll()
    {
        return $this->sample->with('sampleMethod', 'province', 'regency', 'district', 'village', 'createdBy', 'updatedBy')->active()->orderBy('created_at', 'desc')->get();
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
                'description' => $attributes['description'] ?? null,
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

    public function getAllRegency()
    {
        $sample = $this->sample->active()->with('detailSampleViruses', 'detailSampleViruses.virus', 'detailSampleViruses.detailSampleMorphotypes')->get();

        $data = [];
        foreach ($sample as $key => $value) {
            $data[$key]['regency_id'] = $value->regency_id;
            $data[$key]['regency'] = $value->regency->name;
            $data[$key]['location'] = $value->latitude . ', ' . $value->longitude;
            $data[$key]['count'] = $value->detailSampleViruses->map(function ($item) {
                $amount = 0;
                $item->detailSampleMorphotypes->map(function ($item) use (&$amount) {
                    $amount += $item->amount;
                });
                return $amount;
            })->sum();
            $data[$key]['type'] = $value->detailSampleViruses->map(function ($item) {
                return [
                    'name' => $item->virus->name,
                    'amount' => $item->detailSampleMorphotypes->map(function ($item) {
                        return $item->amount;
                    })->sum(),
                ];
            });
        }

        // sum amount of same regency by index
        $data = collect($data)->groupBy('regency')->map(function ($item) {
            $amount = 0;
            $type = [];
            foreach ($item as $key => $value) {
                $amount += $value['count'];
                foreach ($value['type'] as $key => $value) {
                    if (isset($type[$value['name']])) {
                        $type[$value['name']] += $value['amount'];
                    } else {
                        $type[$value['name']] = $value['amount'];
                    }
                }
            }
            return [
                'regency_id' => $item[0]['regency_id'],
                'regency' => $item[0]['regency'],
                'location' => $item[0]['location'],
                'count' => $amount,
                'type' => $type,
            ];
        });

        // change index to number
        $data = $data->values();

        $data = $data->map(function ($item) {
            $item['type'] = collect($item['type'])->map(function ($item, $key) {
                return [
                    'name' => $key,
                    'amount' => $item,
                ];
            });
            return $item;
        });


        return $data;
    }

    public function getAllGroupByDistrict($regency_id)
    {
        $sample = $this->sample->active()->with('detailSampleViruses', 'detailSampleViruses.virus', 'detailSampleViruses.detailSampleMorphotypes')->where('regency_id', $regency_id)->get();

        $data = [];
        foreach ($sample as $key => $value) {
            $data[$key]['district_id'] = $value->district_id;
            $data[$key]['district'] = $value->district->name;
            $data[$key]['regency'] = $value->regency->name;
            $data[$key]['latitude'] = $value->latitude;
            $data[$key]['longitude'] = $value->longitude;
            $data[$key]['count'] = $value->detailSampleViruses->map(function ($item) {
                $amount = 0;
                $item->detailSampleMorphotypes->map(function ($item) use (&$amount) {
                    $amount += $item->amount;
                });
                return $amount;
            })->sum();
            $data[$key]['type'] = $value->detailSampleViruses->map(function ($item) {
                return [
                    'name' => $item->virus->name,
                    'amount' => $item->detailSampleMorphotypes->map(function ($item) {
                        return $item->amount;
                    })->sum(),
                ];
            });
            $data[$key]['created_at'] = $value->created_at->format('Y-m-d');
        }

        // sum amount of same district by index
        $data = collect($data)->groupBy('district')->map(function ($item) {
            $amount = 0;
            foreach ($item as $key => $value) {
                $amount += $value['count'];
            }
            return [
                'district_id' => $item[0]['district_id'],
                'district' => $item[0]['district'],
                'regency' => $item[0]['regency'],
                'latitude' => $item[0]['latitude'],
                'longitude' => $item[0]['longitude'],
                'count' => $amount,
                'type' => $item[0]['type'],
                'created_at' => $item[0]['created_at'],
            ];
        });

        // change index to number
        $data = $data->values();

        return $data;
    }

    public function getAllGroupByDistrictFilterByMonth($regency_id, $month)
    {
        $sample = $this->sample->active()->with('detailSampleViruses', 'detailSampleViruses.virus', 'detailSampleViruses.detailSampleMorphotypes')->where([
            ['regency_id', $regency_id],
            [DB::raw('MONTH(created_at)'), $month],
        ])->get();

        // dd($sample);

        $data = [];
        foreach ($sample as $key => $value) {
            $data[$key]['district_id'] = $value->district_id;
            $data[$key]['district'] = $value->district->name;
            $data[$key]['regency'] = $value->regency->name;
            $data[$key]['latitude'] = $value->latitude;
            $data[$key]['longitude'] = $value->longitude;
            $data[$key]['count'] = $value->detailSampleViruses->map(function ($item) {
                $amount = 0;
                $item->detailSampleMorphotypes->map(function ($item) use (&$amount) {
                    $amount += $item->amount;
                });
                return $amount;
            })->sum();
            $data[$key]['type'] = $value->detailSampleViruses->map(function ($item) {
                return [
                    'name' => $item->virus->name,
                    'amount' => $item->detailSampleMorphotypes->map(function ($item) {
                        return $item->amount;
                    })->sum(),
                ];
            });
            $data[$key]['created_at'] = $value->created_at->format('Y-m-d');
        }

        // sum amount of same district by index
        $data = collect($data)->groupBy('district')->map(function ($item) {
            $amount = 0;
            foreach ($item as $key => $value) {
                $amount += $value['count'];
            }
            return [
                'district_id' => $item[0]['district_id'],
                'district' => $item[0]['district'],
                'regency' => $item[0]['regency'],
                'latitude' => $item[0]['latitude'],
                'longitude' => $item[0]['longitude'],
                'count' => $amount,
                'type' => $item[0]['type'],
                'created_at' => $item[0]['created_at'],
            ];
        });

        // change index to number
        $data = $data->values();

        return $data;
    }

    public function getAllGroupByDistrictFilterByDateRange($regency_id, $start_date, $end_date)
    {
        $start_date = date('Y-m-d', strtotime($start_date));
        $end_date = date('Y-m-d', strtotime($end_date));
        $sample = $this->sample->active()->with('detailSampleViruses', 'detailSampleViruses.virus', 'detailSampleViruses.detailSampleMorphotypes')->where([
            ['regency_id', $regency_id],
            ['created_at', '>=', $start_date],
            ['created_at', '<=', $end_date],
        ])->get();

        $data = [];
        foreach ($sample as $key => $value) {
            $data[$key]['district_id'] = $value->district_id;
            $data[$key]['district'] = $value->district->name;
            $data[$key]['regency'] = $value->regency->name;
            $data[$key]['latitude'] = $value->latitude;
            $data[$key]['longitude'] = $value->longitude;
            $data[$key]['count'] = $value->detailSampleViruses->map(function ($item) {
                $amount = 0;
                $item->detailSampleMorphotypes->map(function ($item) use (&$amount) {
                    $amount += $item->amount;
                });
                return $amount;
            })->sum();
            $data[$key]['type'] = $value->detailSampleViruses->map(function ($item) {
                return [
                    'name' => $item->virus->name,
                    'amount' => $item->detailSampleMorphotypes->map(function ($item) {
                        return $item->amount;
                    })->sum(),
                ];
            });
            $data[$key]['created_at'] = $value->created_at->format('Y-m-d');
        }

        // sum amount of same district by index
        $data = collect($data)->groupBy('district')->map(function ($item) {
            $amount = 0;
            foreach ($item as $key => $value) {
                $amount += $value['count'];
            }
            return [
                'district_id' => $item[0]['district_id'],
                'district' => $item[0]['district'],
                'regency' => $item[0]['regency'],
                'latitude' => $item[0]['latitude'],
                'longitude' => $item[0]['longitude'],
                'count' => $amount,
                'type' => $item[0]['type'],
                'created_at' => $item[0]['created_at'],
            ];
        });

        // change index to number
        $data = $data->values();

        return $data;
    }
}
