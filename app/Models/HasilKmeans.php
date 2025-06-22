<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HasilKmeans extends Model
{
    use HasFactory;

    protected $table = 'hasil_kmeans';

    protected $fillable = [
        'penduduk_id',
        'centroid_id',
        'cluster',
        'jarak',
        'iterasi',
        'tahun',
        'periode'
    ];

    protected $casts = [
        'skor_kelayakan' => 'float',
        'skor_penghasilan' => 'float',
        'skor_tanggungan' => 'float',
        'skor_kondisi_rumah' => 'float',
        'skor_status_kepemilikan' => 'float',
        'skor_usia' => 'float'
    ];

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class);
    }

    public function centroid(): BelongsTo
    {
        return $this->belongsTo(Centroid::class);
    }
}