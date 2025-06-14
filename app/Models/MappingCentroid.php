<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MappingCentroid extends Model
{
    use HasFactory;

    protected $fillable = [
        'penduduk_id',
        'centroid_id',
        'jarak_euclidean',
        'cluster',
        'status_kelayakan',
        'keterangan'
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
