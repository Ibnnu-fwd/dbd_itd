<?php

namespace App\Repositories\Interface;

interface ClusteringInterface
{
    public function calculateDistance($dataset);
    public function processCluster($dataset, $epsilon, $minPts);
}
