<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;

    protected $table = 'kriteria';

    protected $fillable = [
        'nama',
        'deskripsi',
        'nama_kriteria',
        'tipe_kriteria',
        'min',
        'max',
        'nilai',
        'is_aktif'
    ];

    protected $casts = [
        'nilai' => 'integer',
        'is_aktif' => 'boolean'
    ];

 
    public function nilaiKriteria()
    {
        return $this->hasMany(NilaiKriteria::class);
    }

}
