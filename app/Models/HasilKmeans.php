<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilKmeans extends Model
{
    use HasFactory;

    protected $table = 'hasil_kmeans';

    protected $fillable = [
        'penduduk_id',
        'centroid_id',
        'cluster',
        'skor_kelayakan',
        'skor_penghasilan',
        'skor_tanggungan',
        'skor_kondisi_rumah',
        'skor_status_kepemilikan',
        'skor_usia',
        'kelayakan'
    ];

    protected $casts = [
        'skor_kelayakan' => 'float',
        'skor_penghasilan' => 'float',
        'skor_tanggungan' => 'float',
        'skor_kondisi_rumah' => 'float',
        'skor_status_kepemilikan' => 'float',
        'skor_usia' => 'float'
    ];

    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class);
    }

    public function centroid()
    {
        return $this->belongsTo(Centroid::class);
    }
}