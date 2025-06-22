<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penduduk extends Model
{
    use HasFactory;

    protected $table = 'penduduk';
    protected $fillable = [
        'user_id',
        'no',
        'nik',
        'nama',
        'tahun',
        'jenis_kelamin',
        'usia',
        'rt',
        'tanggungan',
        'kondisi_rumah',
        'status_kepemilikan',
        'penghasilan',
        'is_profile_complete',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'usia' => 'integer',
        'rt' => 'integer',
        'tanggungan' => 'integer',
        'penghasilan' => 'float',
        'is_profile_complete' => 'boolean',
    ];

    /**
     * Get the user that owns the penduduk record.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the hasil kmeans record associated with the penduduk.
     */
    public function hasilKmeans()
    {
        return $this->hasOne(HasilKmeans::class);
    }
}
