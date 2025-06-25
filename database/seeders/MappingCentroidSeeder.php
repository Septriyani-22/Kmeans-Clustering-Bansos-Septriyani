<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MappingCentroidSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('mapping_centroids')->truncate();
        DB::table('mapping_centroids')->insert([
            [
                'data_ke' => 4,
                'nama_penduduk' => 'Riduan',
                'cluster' => 'C1',
                'usia' => 4,
                'jumlah_tanggungan' => 3,
                'kondisi_rumah' => 3,
                'status_kepemilikan' => 2,
                'jumlah_penghasilan' => 4.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'data_ke' => 7,
                'nama_penduduk' => 'UJANG',
                'cluster' => 'C2',
                'usia' => 4,
                'jumlah_tanggungan' => 4,
                'kondisi_rumah' => 2,
                'status_kepemilikan' => 1,
                'jumlah_penghasilan' => 4.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'data_ke' => 10,
                'nama_penduduk' => 'ISMAIL',
                'cluster' => 'C3',
                'usia' => 4,
                'jumlah_tanggungan' => 3,
                'kondisi_rumah' => 1,
                'status_kepemilikan' => 1,
                'jumlah_penghasilan' => 2.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
} 