<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penduduk extends Model
{
    use HasFactory;

    protected $table = 'penduduk';
    protected $fillable = [
        'nik',
        'nama',
        'usia',
        'tanggungan',
        'kondisi_rumah',
        'status_kepemilikan',
        'penghasilan',
        'cluster'
    ];

    // Get cluster name
    public function getClusterNameAttribute()
    {
        switch ($this->cluster) {
            case 1:
                return 'Membutuhkan';
            case 2:
                return 'Tidak Membutuhkan';
            case 3:
                return 'Prioritas Sedang';
            default:
                return 'Unknown';
        }
    }

    // Get formatted penghasilan
    public function getFormattedPenghasilanAttribute()
    {
        return 'Rp ' . number_format($this->penghasilan, 0, ',', '.');
    }

    // Get nilai kriteria
    public function getNilaiKriteria()
    {
        return [
            'usia' => Kriteria::getNilaiUsia($this->usia),
            'tanggungan' => Kriteria::getNilaiTanggungan($this->tanggungan),
            'kondisi_rumah' => Kriteria::getNilaiKondisiRumah($this->kondisi_rumah),
            'status_kepemilikan' => Kriteria::getNilaiStatusKepemilikan($this->status_kepemilikan),
            'penghasilan' => Kriteria::getNilaiPenghasilan($this->penghasilan)
        ];
    }

    // Get kriteria options
    public function getKriteriaOptions()
    {
        return Kriteria::getKriteriaOptions();
    }

    // Add any necessary accessors or mutators here
    public function getJenisKelaminTextAttribute()
    {
        return $this->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan';
    }
}
