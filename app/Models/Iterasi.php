<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Iterasi extends Model
{
    protected $table = 'iterasi';
    protected $fillable = [
        'iterasi',
        'cluster',
        'usia',
        'tanggungan',
        'kondisi_rumah',
        'status_kepemilikan',
        'penghasilan'
    ];

} 