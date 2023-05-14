<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TpaType extends Model
{
    use HasFactory;

    public $table = 'tpa_types';

    protected $fillable = [
        'name',
        'is_active'
    ];
}
