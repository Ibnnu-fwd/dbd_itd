<?php

namespace App\Repositories;

use App\Repositories\Interface\ClusteringInterface;
use Phpml\Classification\KNearestNeighbors;
use Phpml\Clustering\DBSCAN;
use Phpml\Math\Distance\Euclidean;

class ClusteringRepository implements ClusteringInterface
{
    public function calculateDistance($dataset)
    {
        $sample = $dataset->map(function ($item) {
            return [
                $item->latitude,
                $item->longitude,
            ];
        })->toArray();

        $distance = $this->euclideanDistance($sample);
        return $distance;
    }

    public function euclideanDistance($sample)
    {
        $euclidean = new Euclidean();
        $distance  = [];
        for ($i = 0; $i < count($sample); $i++) {
            for ($j = 0; $j < count($sample); $j++) {
                $distance[$i][$j] = $euclidean->distance($sample[$i], $sample[$j]);
            }
        }

        return $distance;
    }

    public function processCluster($dataset, $epsilon, $minSamples)
    {
        $dbscan   = new DBSCAN($epsilon, $minSamples);
        $clusters = $dbscan->cluster($dataset);
        return $clusters;
    }
}
