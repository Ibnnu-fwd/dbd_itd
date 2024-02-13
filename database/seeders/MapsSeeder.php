<?php

namespace Database\Seeders;

use App\Models\Map;
use Illuminate\Database\Seeder;

class MapsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(public_path('assets/geojson/surabaya.geojson'));
        $json = json_decode($json, true);
        foreach ($json as $value) {
            Map::create([
                'province' => $value['province'],
                'regency' => $value['district'],
                'district' => $value['sub_district'],
                'village' => $value['village'],
                'coordinates' => $value['border'],
            ]);
        }
    }
}
