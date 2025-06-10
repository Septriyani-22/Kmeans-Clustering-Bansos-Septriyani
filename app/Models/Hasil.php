<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hasil extends Model
{
    protected $table = 'hasil';
    protected $fillable = [
        'penduduk_id',
        'cluster',
        'jarak',
        'iterasi'
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

    // Get formatted jarak
    public function getFormattedJarakAttribute()
    {
        return number_format($this->jarak, 4);
    }

    // Get penduduk
    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class);
    }

    // Get centroid
    public function centroid()
    {
        return $this->belongsTo(Centroid::class, 'cluster', 'cluster');
    }
} 