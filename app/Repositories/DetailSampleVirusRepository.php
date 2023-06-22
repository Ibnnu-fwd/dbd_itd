<?php

namespace App\Repositories;

use App\Models\DetailSampleMorphotype;
use App\Models\DetailSampleSerotype;
use App\Models\DetailSampleVirus;
use App\Repositories\Interface\DetailSampleVirusInterface;
use Illuminate\Support\Facades\DB;

class DetailSampleVirusRepository implements DetailSampleVirusInterface
{
    private $detailSampleVirus;
    private $detailSampleMorphotype;
    private $detailSampleSerotype;

    public function __construct(
        DetailSampleVirus $detailSampleVirus,
        DetailSampleMorphotype $detailSampleMorphotype,
        DetailSampleSerotype $detailSampleSerotype
    ) {
        $this->detailSampleVirus        = $detailSampleVirus;
        $this->detailSampleMorphotype   = $detailSampleMorphotype;
        $this->detailSampleSerotype     = $detailSampleSerotype;
    }

    public function getById($id)
    {
        return $this->detailSampleVirus->with('detailSampleMorphotypes', 'detailSampleMorphotypes.morphotype')->find($id);
    }

    public function store($attributes, $detailSampleVirusId)
    {
        $sample_id = $this->detailSampleVirus->find($detailSampleVirusId)->sample_id;

        $this->detailSampleMorphotype->create([
            'detail_sample_virus_id'    => $detailSampleVirusId,
            'morphotype_id'             => 1,
            'amount' => $attributes['morphotype_1'] ?? 0,
        ]);

        $this->detailSampleMorphotype->create([
            'detail_sample_virus_id'    => $detailSampleVirusId,
            'morphotype_id'             => 2,
            'amount' => $attributes['morphotype_2'] ?? 0,
        ]);

        $this->detailSampleMorphotype->create([
            'detail_sample_virus_id'    => $detailSampleVirusId,
            'morphotype_id'             => 3,
            'amount' => $attributes['morphotype_3'] ?? 0,
        ]);

        $this->detailSampleMorphotype->create([
            'detail_sample_virus_id'    => $detailSampleVirusId,
            'morphotype_id'             => 4,
            'amount' => $attributes['morphotype_4'] ?? 0,
        ]);

        $this->detailSampleMorphotype->create([
            'detail_sample_virus_id'    => $detailSampleVirusId,
            'morphotype_id'             => 5,
            'amount' => $attributes['morphotype_5'] ?? 0,
        ]);

        $this->detailSampleMorphotype->create([
            'detail_sample_virus_id'    => $detailSampleVirusId,
            'morphotype_id'             => 6,
            'amount' => $attributes['morphotype_6'] ?? 0,
        ]);

        $this->detailSampleMorphotype->create([
            'detail_sample_virus_id'    => $detailSampleVirusId,
            'morphotype_id'             => 7,
            'amount' => $attributes['morphotype_7'] ?? 0,
        ]);

        $this->detailSampleMorphotype->create([
            'detail_sample_virus_id' => $detailSampleVirusId,
            'morphotype_id'          => 8,
            'amount' => $attributes['unidentified'] ?? 0,
        ]);

        $this->detailSampleSerotype->create([
            'sample_id' => $sample_id,
            'serotype_id' => 1,
            'status' => $attributes['denv_1'] ?? 0,
        ]);

        $this->detailSampleSerotype->create([
            'sample_id' => $sample_id,
            'serotype_id' => 2,
            'status' => $attributes['denv_2'] ?? 0,
        ]);

        $this->detailSampleSerotype->create([
            'sample_id' => $sample_id,
            'serotype_id' => 3,
            'status' => $attributes['denv_3'] ?? 0,
        ]);

        $this->detailSampleSerotype->create([
            'sample_id' => $sample_id,
            'serotype_id' => 4,
            'status' => $attributes['denv_4'] ?? 0,
        ]);

        return true;
    }

    public function update($attributes, $detailSampleVirusId)
    {
        $sample_id = $this->detailSampleVirus->find($detailSampleVirusId)->sample_id;

        $this->detailSampleMorphotype->where('detail_sample_virus_id', $detailSampleVirusId)->where('morphotype_id', 1)->update([
            'amount' => $attributes['morphotype_1'] ?? 0,
        ]);

        $this->detailSampleMorphotype->where('detail_sample_virus_id', $detailSampleVirusId)->where('morphotype_id', 2)->update([
            'amount' => $attributes['morphotype_2'] ?? 0,
        ]);

        $this->detailSampleMorphotype->where('detail_sample_virus_id', $detailSampleVirusId)->where('morphotype_id', 3)->update([
            'amount' => $attributes['morphotype_3'] ?? 0,
        ]);

        $this->detailSampleMorphotype->where('detail_sample_virus_id', $detailSampleVirusId)->where('morphotype_id', 4)->update([
            'amount' => $attributes['morphotype_4'] ?? 0,
        ]);

        $this->detailSampleMorphotype->where('detail_sample_virus_id', $detailSampleVirusId)->where('morphotype_id', 5)->update([
            'amount' => $attributes['morphotype_5'] ?? 0,
        ]);

        $this->detailSampleMorphotype->where('detail_sample_virus_id', $detailSampleVirusId)->where('morphotype_id', 6)->update([
            'amount' => $attributes['morphotype_6'] ?? 0,
        ]);

        $this->detailSampleMorphotype->where('detail_sample_virus_id', $detailSampleVirusId)->where('morphotype_id', 7)->update([
            'amount' => $attributes['morphotype_7'] ?? 0,
        ]);

        // update or create unidentified morphotype
        $this->detailSampleMorphotype->updateOrCreate(
            [
                'detail_sample_virus_id' => $detailSampleVirusId,
                'morphotype_id' => 8,
            ],
            [
                'amount' => $attributes['unidentified'] ?? 0,
            ]
        );

        $this->detailSampleSerotype->where('sample_id', $sample_id)->where('serotype_id', 1)->update([
            'status' => $attributes['denv_1'] ?? 0,
        ]);

        $this->detailSampleSerotype->where('sample_id', $sample_id)->where('serotype_id', 2)->update([
            'status' => $attributes['denv_2'] ?? 0,
        ]);

        $this->detailSampleSerotype->where('sample_id', $sample_id)->where('serotype_id', 3)->update([
            'status' => $attributes['denv_3'] ?? 0,
        ]);

        $this->detailSampleSerotype->where('sample_id', $sample_id)->where('serotype_id', 4)->update([
            'status' => $attributes['denv_4'] ?? 0,
        ]);

        return true;
    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {
            $this->detailSampleSerotype->whereHas('sample', function ($query) use ($id) {
                $query->where('sample_id', $id);
            })->delete();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        try {
            $this->detailSampleMorphotype->where('detail_sample_virus_id', $id)->delete();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        try {
            $this->detailSampleVirus->where('id', $id)->delete();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        DB::commit();
    }

    public function deleteDetailSampleVirusMorphotype($detailSampleMorphotypeId)
    {
        DB::beginTransaction();

        try {
            $this->detailSampleSerotype->where('detail_sample_morphotype_id', $detailSampleMorphotypeId)->delete();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        try {
            $this->detailSampleMorphotype->where('id', $detailSampleMorphotypeId)->delete();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        DB::commit();
    }
}
