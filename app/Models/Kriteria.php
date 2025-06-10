<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;

    protected $table = 'kriteria'; 

    protected $fillable = [
        'nama_kriteria',
        'nilai',
        'keterangan',
        'is_aktif'
    ];

    protected $casts = [
        'nilai' => 'integer',
        'is_aktif' => 'boolean'
    ];
}
