<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penduduk extends Model
{
    use HasFactory;

    protected $table = 'penduduk';
    protected $fillable = [
        'no',
        'nik',
        'nama',
        'tahun',
        'jenis_kelamin',
        'usia',
        'rt',
        'tanggungan',
        'kondisi_rumah',
        'status_kepemilikan',
        'penghasilan'
    ];

    protected $casts = [
        'tahun' => 'integer',
        'usia' => 'integer',
        'rt' => 'integer',
        'tanggungan' => 'integer',
        'penghasilan' => 'float'
    ];
}
