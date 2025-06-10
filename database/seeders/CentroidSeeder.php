<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Centroid;

class CentroidSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data centroid yang ada
        Centroid::truncate();

        // Buat centroid awal
        $centroids = [
            [
                'nama_centroid' => 'Cluster 1',
                'penghasilan_num' => 1500000, // 1.5 juta
                'tanggungan_num' => 5,
                'tahun' => 2024,
                'periode' => 1,
                'keterangan' => 'Kelompok dengan penghasilan rendah dan tanggungan banyak'
            ],
            [
                'nama_centroid' => 'Cluster 2',
                'penghasilan_num' => 3000000, // 3 juta
                'tanggungan_num' => 3,
                'tahun' => 2024,
                'periode' => 1,
                'keterangan' => 'Kelompok dengan penghasilan menengah dan tanggungan sedang'
            ],
            [
                'nama_centroid' => 'Cluster 3',
                'penghasilan_num' => 5000000, // 5 juta
                'tanggungan_num' => 2,
                'tahun' => 2024,
                'periode' => 1,
                'keterangan' => 'Kelompok dengan penghasilan tinggi dan tanggungan sedikit'
            ]
        ];

        foreach ($centroids as $centroid) {
            Centroid::create($centroid);
        }
    }
} 