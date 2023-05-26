<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationType extends Model
{
    use HasFactory;

    public $table = 'location_types';

    protected $fillable = [
        'name',
        'is_active'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    // check unique name in active location type
    public function scopeCheckUniqueName($query, $name)
    {
        return $query->where('name', $name)->active();
    }
}
