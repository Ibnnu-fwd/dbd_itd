<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cluster extends Model
{
    use HasFactory;

    protected $fillable = [
        'sample_code',
        'date',
        'province',
        'regency',
        'district',
        'village',
        'location_type',
        'location_name',
        'latitude',
        'longitude',
        'aedes_aegypti',
        'aedes_albopictus',
        'culex',
        'morphotype_1',
        'morphotype_2',
        'morphotype_3',
        'morphotype_4',
        'morphotype_5',
        'morphotype_6',
        'morphotype_7',
        'denv_1',
        'denv_2',
        'denv_3',
        'denv_4',
    ];
}
