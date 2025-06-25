<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanRiwayat extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_riwayat';
    protected $fillable = [
        'penduduk_id',
        'aksi',
        'keterangan',
        'status',
        'data_lama',
        'data_baru',
    ];

    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class);
    }
} 