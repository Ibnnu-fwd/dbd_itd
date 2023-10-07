<?php

namespace App\Repositories;

use App\Models\TCases;
use App\Repositories\Interface\TCasesInterface;

class TCasesRepository implements TCasesInterface
{
    protected $model;
    private $TCases;
    public function __construct(TCases $TCases)
    {
        $this->TCases = $TCases;
    }

    public function all()
    {
        return $this->model->where('is_active', true) // Filter data utama Ksh
        ->get();
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }
    public function getById($id)
    {
        return $this->TCases->active()->findOrFail($id);
    }
    public function create($attributes)
    {
        return $this->TCases->create([
            'date'  => $attributes['date'],
            'vector_type' => $attributes['vector_type'],
            'cases_total' => $attributes['cases_total'],
            'regency_id' => $attributes['regency_id'],
            'district_id' => $attributes['district_id'],
            'village_id'  => $attributes['village_id'],
        ]);
    }

    public function update($id, array $attributes)
    {
        $TCases = $this->TCases->findOrFail($id);

        return $TCases->update([
            'date' => $attributes['date'],
            'vector_type' => $attributes['vector_type'] ?? null,
            'cases_total' => $attributes['cases_total'] ?? null,
            'regency_id' => $attributes['regency_id'] ?? null,
            'district_id' => $attributes['district_id'] ?? null,
            'village_id' => $attributes['village_id'] ?? null,
        ]);
    }
    public function delete($id)
    {
        $TCases = $this->TCases->findOrFail($id);
        $TCases->is_active = false;
        $TCases->save();

        return $TCases;
    }
    public function getGeoJson()
    {
    }
}
