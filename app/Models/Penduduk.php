<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penduduk extends Model
{
    use HasFactory;

    protected $fillable = [
        'nik', 'nama', 'tahun', 'jenis_kelamin', 'usia', 'rt', 'tanggungan', 
        'kondisi_rumah', 'status_kepemilikan', 'penghasilan'
    ];

    // Add any necessary accessors or mutators here
    public function getJenisKelaminTextAttribute()
    {
        return $this->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan';
    }
}
