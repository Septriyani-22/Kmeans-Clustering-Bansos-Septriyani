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