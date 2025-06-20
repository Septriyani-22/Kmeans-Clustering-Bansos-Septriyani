<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Centroid extends Model
{
    use HasFactory;

    protected $table = 'centroids';
    protected $fillable = [
        'usia',
        'tanggungan_num',
        'kondisi_rumah',
        'status_kepemilikan',
        'penghasilan_num',
        'tahun',
        'periode'
    ];

    protected $casts = [
        'usia' => 'float',
        'tanggungan_num' => 'float',
        'penghasilan_num' => 'float',
        'tahun' => 'integer',
        'periode' => 'integer'
    ];
}
