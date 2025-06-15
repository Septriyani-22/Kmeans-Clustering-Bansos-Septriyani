<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MappingCentroid extends Model
{
    use HasFactory;

    protected $table = 'mapping_centroids';

    protected $fillable = [
        'data_ke',
        'cluster',
        'jarak',
        'nama_penduduk',
        'usia',
        'jumlah_tanggungan',
        'kondisi_rumah',
        'status_kepemilikan',
        'jumlah_penghasilan'
    ];

    public function centroid()
    {
        return $this->belongsTo(Centroid::class);
    }

    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class, 'data_ke', 'id');
    }

    // Accessors for Penduduk attributes
    public function getNamaPendudukAttribute()
    {
        return $this->penduduk->nama ?? '';
    }

    public function getUsiaAttribute()
    {
        return $this->penduduk->usia ?? 0;
    }

    public function getTanggunganAttribute()
    {
        return $this->penduduk->tanggungan ?? 0;
    }

    public function getKondisiRumahAttribute()
    {
        return $this->penduduk->kondisi_rumah ?? '';
    }

    public function getStatusKepemilikanAttribute()
    {
        return $this->penduduk->status_kepemilikan ?? '';
    }

    public function getJumlahPenghasilanAttribute()
    {
        return $this->penduduk->penghasilan ?? 0;
    }
}
