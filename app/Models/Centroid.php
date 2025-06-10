<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Centroid extends Model
{
    use HasFactory;

    protected $table = 'centroids';
    protected $fillable = [
        'usia',
        'tanggungan',
        'kondisi_rumah',
        'status_kepemilikan',
        'penghasilan',
        'cluster'
    ];

    protected $casts = [
        'penghasilan_num' => 'float',
        'tanggungan_num' => 'integer',
        'usia_num' => 'integer',
        'kondisi_rumah_num' => 'integer',
        'status_kepemilikan_num' => 'integer',
        'tahun' => 'integer',
        'periode' => 'integer'
    ];

    public function hasilKmeans()
    {
        return $this->hasMany(HasilKmeans::class);
    }

    public static function mapKondisiRumah($kondisiRumah)
    {
        switch (strtolower($kondisiRumah)) {
            case 'baik': return 1;
            case 'cukup': return 2;
            case 'kurang': return 3;
            default: return 0; 
        }
    }

    public static function mapStatusKepemilikan($statusKepemilikan)
    {
        switch (strtolower($statusKepemilikan)) {
            case 'hak milik': return 1; 
            case 'numpang': return 2; 
            case 'sewa': return 3; 
            default: return 0;
        }
    }

    public static function mapUsia($usia)
    {
        if ($usia > 46) return 4;
        if ($usia >= 36) return 3;
        if ($usia >= 25) return 2;
        if ($usia >= 15) return 1;
        return 0;
    }

    public static function normalizeValues($penghasilan, $tanggungan, $usia, $kondisiRumah, $statusKepemilikan)
    {
        // Map categorical values to numerical before normalization
        $kondisiRumahNum = self::mapKondisiRumah($kondisiRumah);
        $statusKepemilikanNum = self::mapStatusKepemilikan($statusKepemilikan);
        $usiaNum = self::mapUsia($usia);

        $data = [ // Values to be normalized
            'penghasilan' => floatval($penghasilan),
            'tanggungan' => intval($tanggungan),
            'usia' => $usiaNum,
            'kondisi_rumah' => $kondisiRumahNum,
            'status_kepemilikan' => $statusKepemilikanNum
        ];

        $normalized = [];
        $minValues = [];
        $maxValues = [];

        // Dynamically get min/max for each relevant column from the Centroid model
        foreach (['penghasilan_num', 'tanggungan_num', 'usia_num', 'kondisi_rumah_num', 'status_kepemilikan_num'] as $column) {
            $minValues[$column] = floatval(self::min($column) ?? 0);
            $maxValues[$column] = floatval(self::max($column) ?? 0);
        }

        // Normalize each value
        foreach ($data as $key => $value) {
            $columnName = $key . '_num';
            $min = $minValues[$columnName];
            $max = $maxValues[$columnName];
            $range = $max - $min;

            // Apply specific normalization logic based on criterion impact
            if ($key === 'penghasilan') { // Lower penghasilan is better (score closer to 1)
                $normalized[$key . '_normal'] = $range > 0 ? 1 - (($value - $min) / $range) : 0.5;
            } elseif ($key === 'tanggungan') { // Higher tanggungan is better (score closer to 1)
                $normalized[$key . '_normal'] = $range > 0 ? ($value - $min) / $range : 0.5;
            } elseif ($key === 'kondisi_rumah') { // Lower (1=kurang) is better
                $normalized[$key . '_normal'] = $range > 0 ? 1 - (($value - $min) / $range) : 0.5;
            } elseif ($key === 'status_kepemilikan') { // Lower (1=numpang) is better
                $normalized[$key . '_normal'] = $range > 0 ? 1 - (($value - $min) / $range) : 0.5;
            } elseif ($key === 'usia') { // Higher (older) is better (assuming older means more need)
                $normalized[$key . '_normal'] = $range > 0 ? ($value - $min) / $range : 0.5;
            }
            
            // Ensure normalized values are between 0 and 1
            $normalized[$key . '_normal'] = max(0, min(1, $normalized[$key . '_normal']));
        }

        return $normalized;
    }

    // Accessor for formatted penghasilan
    public function getPenghasilanFormattedAttribute()
    {
        return 'Rp ' . number_format($this->penghasilan_num, 0, ',', '.');
    }

    // Centroid awal berdasarkan teori
    public static function getInitialCentroids()
    {
        return [
            [
                'usia' => 4,
                'tanggungan' => 3,
                'kondisi_rumah' => 3,
                'status_kepemilikan' => 2,
                'penghasilan' => 4,
                'cluster' => 1 // Membutuhkan
            ],
            [
                'usia' => 4,
                'tanggungan' => 4,
                'kondisi_rumah' => 2,
                'status_kepemilikan' => 1,
                'penghasilan' => 4,
                'cluster' => 2 // Tidak Membutuhkan
            ],
            [
                'usia' => 4,
                'tanggungan' => 3,
                'kondisi_rumah' => 1,
                'status_kepemilikan' => 1,
                'penghasilan' => 2,
                'cluster' => 3 // Prioritas Sedang
            ]
        ];
    }

    // Hitung jarak Euclidean
    public function calculateDistance($penduduk)
    {
        $usia = Kriteria::getNilaiUsia($penduduk->usia);
        $tanggungan = Kriteria::getNilaiTanggungan($penduduk->tanggungan);
        $kondisiRumah = Kriteria::getNilaiKondisiRumah($penduduk->kondisi_rumah);
        $statusKepemilikan = Kriteria::getNilaiStatusKepemilikan($penduduk->status_kepemilikan);
        $penghasilan = Kriteria::getNilaiPenghasilan($penduduk->penghasilan);

        return sqrt(
            pow($usia - $this->usia, 2) +
            pow($tanggungan - $this->tanggungan, 2) +
            pow($kondisiRumah - $this->kondisi_rumah, 2) +
            pow($statusKepemilikan - $this->status_kepemilikan, 2) +
            pow($penghasilan - $this->penghasilan, 2)
        );
    }

    // Update centroid berdasarkan rata-rata cluster
    public function updateCentroid($penduduks)
    {
        if ($penduduks->isEmpty()) return;

        $this->usia = $penduduks->avg('usia');
        $this->tanggungan = $penduduks->avg('tanggungan');
        $this->kondisi_rumah = $penduduks->avg('kondisi_rumah');
        $this->status_kepemilikan = $penduduks->avg('status_kepemilikan');
        $this->penghasilan = $penduduks->avg('penghasilan');
        $this->save();
    }

    // Get cluster name
    public function getClusterNameAttribute()
    {
        switch ($this->cluster) {
            case 1:
                return 'Membutuhkan';
            case 2:
                return 'Tidak Membutuhkan';
            case 3:
                return 'Prioritas Sedang';
            default:
                return 'Unknown';
        }
    }
}
