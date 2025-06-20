<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilClustering extends Model
{
    protected $table = 'hasil_clustering';
    
    protected $fillable = [
        'penduduk_id',
        'cluster'
    ];

    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class);
    }

} 