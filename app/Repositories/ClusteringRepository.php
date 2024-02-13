<?php

namespace App\Repositories;

use App\Repositories\Interface\ClusteringInterface;
use Phpml\Classification\KNearestNeighbors;
use Phpml\Math\Distance\Euclidean;

class ClusteringRepository implements ClusteringInterface
{
    public function doingAllProcess($dataset)
    {
        $sample = $dataset->map(function ($item) {
            return [
                $item->latitude,
                $item->longitude,
            ];
        })->toArray();

        // menentukan nilai k
        $k_value = 6;

        // menghitung knn distance
        $knn = new KNearestNeighbors($k_value);
        $knn->train($sample, range(0, count($sample) - 1));
        $distances = [];
        foreach ($sample as $point) {
            $distances[] = $knn->predict($point);
        }

        // mengambil kNN distance dengan k=6 hingga k=k_value
        $knn_distances = array_map(function ($row) use ($k_value) {
            return is_array($row) ? array_slice($row, 1, $k_value - 1) : [];
        }, $distances);
        dd($knn_distances);
    }

    public function euclideanDistance($sample)
    {
        $euclidean = new Euclidean();
        $distance = [];
        for ($i = 0; $i < count($sample); $i++) {
            for ($j = 0; $j < count($sample); $j++) {
                $distance[$i][$j] = $euclidean->distance($sample[$i], $sample[$j]);
            }
        }

        return $distance;
    }
}
