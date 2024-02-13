<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    use HasFactory;

    public $table = 'maps';

    protected $fillable = [
        'province',
        'regency',
        'district',
        'village',
        'coordinates',
    ];

    // cast coordinates to array
    protected $casts = [
        'coordinates' => 'array',
    ];
}
