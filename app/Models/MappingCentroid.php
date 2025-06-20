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
}
