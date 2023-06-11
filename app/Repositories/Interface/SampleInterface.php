<?php

namespace App\Repositories\Interface;

interface SampleInterface
{
    public function getAll();
    public function getById($id);
    public function create(array $attributes);
    public function update($id, array $attributes);
    public function delete($id);

    public function detailSample($id);
    public function getAllRegency();
    public function getAllGroupByDistrict($regency_id);
    public function getAllGroupByDistrictFilterByMonth($regency_id, $month);
    public function getAllGroupByDistrictFilterByDateRange($regency_id, $start_date, $end_date);

    public function getSamplePerYear($year = null);
    public function getTotalSample();
    public function getTotalMosquito();
}
