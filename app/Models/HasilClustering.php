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

    public function getClusterNameAttribute()
    {
        return match($this->cluster) {
            1 => 'Membutuhkan',
            2 => 'Tidak Membutuhkan',
            3 => 'Prioritas Sedang',
            default => 'Unknown'
        };
    }

    public function getClusterDescriptionAttribute()
    {
        return match($this->cluster) {
            1 => 'Sangat membutuhkan bantuan',
            2 => 'Tidak membutuhkan bantuan',
            3 => 'Prioritas bantuan sedang',
            default => 'Unknown'
        };
    }

    public function getClusterBadgeAttribute()
    {
        return match($this->cluster) {
            1 => 'danger',
            2 => 'success',
            3 => 'warning',
            default => 'secondary'
        };
    }
} 