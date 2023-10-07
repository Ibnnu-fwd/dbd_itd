<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TCases extends Model
{
    use HasFactory;

    protected $table = 't_cases';

    protected $fillable = [
        'date',
        'kecamatan',
        'vector_type',
        'cases_total',
        'regency_id',
        'district_id',
        'village_id',
        'created_by',
    ];

    // RELATION
    public function regency()
    {
        return $this->belongsTo(Regency::class, 'regency_id', 'id');
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function village()
    {
        return $this->belongsTo(Village::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    // SCOPE
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
