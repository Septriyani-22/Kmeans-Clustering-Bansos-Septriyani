<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Centroid extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_centroid',
        'penghasilan_num',
        'tanggungan_num',
        'usia_num',
        'kondisi_rumah_num',
        'status_kepemilikan_num',
        'tahun',
        'periode',
        'keterangan'
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
}
