<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;

    protected $table = 'kriteria';

    protected $fillable = [
        'nama',
        'deskripsi',
        'nama_kriteria',
        'tipe_kriteria',
        'min',
        'max',
        'nilai',
        'is_aktif'
    ];

    protected $casts = [
        'nilai' => 'integer',
        'is_aktif' => 'boolean'
    ];

    const TIPE_USIA = 'Usia';
    const TIPE_TANGGUNGAN = 'Jumlah Tanggungan';
    const TIPE_KONDISI_RUMAH = 'Kondisi Rumah';
    const TIPE_STATUS_KEPEMILIKAN = 'Status Kepemilikan';
    const TIPE_PENGHASILAN = 'Penghasilan';

    public static function getTipeKriteria()
    {
        return [
            self::TIPE_USIA,
            self::TIPE_TANGGUNGAN,
            self::TIPE_KONDISI_RUMAH,
            self::TIPE_STATUS_KEPEMILIKAN,
            self::TIPE_PENGHASILAN
        ];
    }

    // Konversi usia ke nilai numerik
    public static function getNilaiUsia($usia)
    {
        if ($usia >= 60) return 4; // Sangat Membutuhkan
        if ($usia >= 50) return 3; // Membutuhkan
        if ($usia >= 40) return 2; // Cukup Membutuhkan
        return 1; // Tidak Membutuhkan
    }

    // Konversi jumlah tanggungan ke nilai numerik
    public static function getNilaiTanggungan($tanggungan)
    {
        if ($tanggungan >= 5) return 4; // Sangat Membutuhkan
        if ($tanggungan >= 4) return 3; // Membutuhkan
        if ($tanggungan >= 3) return 2; // Cukup Membutuhkan
        return 1; // Tidak Membutuhkan
    }

    // Konversi kondisi rumah ke nilai numerik
    public static function getNilaiKondisiRumah($kondisi)
    {
        switch ($kondisi) {
            case 'Sangat Baik':
                return 1; // Tidak Membutuhkan
            case 'Baik':
                return 2; // Cukup Membutuhkan
            case 'Kurang Baik':
                return 3; // Membutuhkan
            case 'Tidak Layak':
                return 4; // Sangat Membutuhkan
            default:
                return 1;
        }
    }

    // Konversi status kepemilikan ke nilai numerik
    public static function getNilaiStatusKepemilikan($status)
    {
        switch ($status) {
            case 'Milik Sendiri':
                return 1; // Tidak Membutuhkan
            case 'Kontrak':
                return 2; // Cukup Membutuhkan
            case 'Sewa':
                return 3; // Membutuhkan
            case 'Menumpang':
                return 4; // Sangat Membutuhkan
            default:
                return 1;
        }
    }

    // Konversi penghasilan ke nilai numerik
    public static function getNilaiPenghasilan($penghasilan)
    {
        if ($penghasilan <= 1000000) return 4; // Sangat Membutuhkan
        if ($penghasilan <= 2000000) return 3; // Membutuhkan
        if ($penghasilan <= 3000000) return 2; // Cukup Membutuhkan
        return 1; // Tidak Membutuhkan
    }

    // Get kriteria options
    public static function getKriteriaOptions()
    {
        return [
            'usia' => [
                'label' => 'Usia',
                'options' => [
                    '1' => 'Tidak Membutuhkan (< 40 tahun)',
                    '2' => 'Cukup Membutuhkan (40-49 tahun)',
                    '3' => 'Membutuhkan (50-59 tahun)',
                    '4' => 'Sangat Membutuhkan (≥ 60 tahun)'
                ]
            ],
            'tanggungan' => [
                'label' => 'Jumlah Tanggungan',
                'options' => [
                    '1' => 'Tidak Membutuhkan (1-2 orang)',
                    '2' => 'Cukup Membutuhkan (3 orang)',
                    '3' => 'Membutuhkan (4 orang)',
                    '4' => 'Sangat Membutuhkan (≥ 5 orang)'
                ]
            ],
            'kondisi_rumah' => [
                'label' => 'Kondisi Rumah',
                'options' => [
                    '1' => 'Tidak Membutuhkan (Sangat Baik)',
                    '2' => 'Cukup Membutuhkan (Baik)',
                    '3' => 'Membutuhkan (Kurang Baik)',
                    '4' => 'Sangat Membutuhkan (Tidak Layak)'
                ]
            ],
            'status_kepemilikan' => [
                'label' => 'Status Kepemilikan',
                'options' => [
                    '1' => 'Tidak Membutuhkan (Milik Sendiri)',
                    '2' => 'Cukup Membutuhkan (Kontrak)',
                    '3' => 'Membutuhkan (Sewa)',
                    '4' => 'Sangat Membutuhkan (Menumpang)'
                ]
            ],
            'penghasilan' => [
                'label' => 'Penghasilan',
                'options' => [
                    '1' => 'Tidak Membutuhkan (> Rp 3.000.000)',
                    '2' => 'Cukup Membutuhkan (Rp 2.000.000 - Rp 3.000.000)',
                    '3' => 'Membutuhkan (Rp 1.000.000 - Rp 2.000.000)',
                    '4' => 'Sangat Membutuhkan (≤ Rp 1.000.000)'
                ]
            ]
        ];
    }

    public function nilaiKriteria()
    {
        return $this->hasMany(NilaiKriteria::class);
    }

    // Get nilai berdasarkan kriteria dan nilai input
    public function getNilai($input)
    {
        $nilaiKriteria = $this->nilaiKriteria()
            ->where('nilai_min', '<=', $input)
            ->where('nilai_max', '>=', $input)
            ->first();

        return $nilaiKriteria ? $nilaiKriteria->nilai : 1;
    }

    // Get semua opsi nilai untuk kriteria ini
    public function getNilaiOptions()
    {
        return $this->nilaiKriteria()
            ->orderBy('nilai')
            ->get()
            ->map(function($nilai) {
                return [
                    'nilai' => $nilai->nilai,
                    'label' => $nilai->nama,
                    'keterangan' => $nilai->keterangan
                ];
            });
    }
}
