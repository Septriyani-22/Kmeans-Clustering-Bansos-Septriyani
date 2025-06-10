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

    // Get formatted nilai
    public function getFormattedNilaiAttribute()
    {
        return [
            'usia' => number_format($this->usia, 4),
            'tanggungan' => number_format($this->tanggungan, 4),
            'kondisi_rumah' => number_format($this->kondisi_rumah, 4),
            'status_kepemilikan' => number_format($this->status_kepemilikan, 4),
            'penghasilan' => number_format($this->penghasilan, 4)
        ];
    }
} 