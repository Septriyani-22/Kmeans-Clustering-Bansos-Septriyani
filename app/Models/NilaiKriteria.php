<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiKriteria extends Model
{
    protected $table = 'nilai_kriteria';
    protected $fillable = [
        'kriteria_id',
        'nama',
        'nilai',
        'nilai_min',
        'nilai_max'
    ];

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }
} 