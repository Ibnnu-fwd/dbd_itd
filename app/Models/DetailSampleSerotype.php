<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailSampleSerotype extends Model
{
    use HasFactory;

    public $table = 'detail_sample_serotypes';

    protected $fillable = [
        'sample_id',
        'serotype_id',
        'status',
    ];

    // RELATIONSHIPS
    public function serotype()
    {
        return $this->belongsTo(Serotype::class, 'serotype_id');
    }

    public function sample()
    {
        return $this->belongsTo(Sample::class, 'sample_id');
    }
}
