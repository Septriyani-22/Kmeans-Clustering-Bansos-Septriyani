<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kriteria;
use App\Models\NilaiKriteria;

class KriteriaSeeder extends Seeder
{
    public function run(): void
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('nilai_kriteria')->truncate();
        \DB::table('kriteria')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Kriteria Usia
        $usia = Kriteria::create([
            'nama' => 'Usia',
            'deskripsi' => 'Kriteria berdasarkan usia kepala keluarga',
            'tipe_kriteria' => 'usia'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $usia->id,
            'nama' => '15-25 Tahun',
            'nilai' => 1,
            'nilai_min' => 15,
            'nilai_max' => 25,
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $usia->id,
            'nama' => '26-35 Tahun',
            'nilai' => 2,
            'nilai_min' => 26,
            'nilai_max' => 35,
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $usia->id,
            'nama' => '36-45 Tahun',
            'nilai' => 3,
            'nilai_min' => 36,
            'nilai_max' => 45,
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $usia->id,
            'nama' => '>46 Tahun',
            'nilai' => 4,
            'nilai_min' => 46,
            'nilai_max' => 100,
        ]);

        // Kriteria Jumlah Tanggungan
        $tanggungan = Kriteria::create([
            'nama' => 'Tanggungan',
            'deskripsi' => 'Kriteria berdasarkan jumlah anggota keluarga yang ditanggung',
            'tipe_kriteria' => 'tanggungan'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $tanggungan->id,
            'nama' => '1 Anak',
            'nilai' => 1,
            'nilai_min' => 1,
            'nilai_max' => 1,
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $tanggungan->id,
            'nama' => '2 Anak',
            'nilai' => 2,
            'nilai_min' => 2,
            'nilai_max' => 2,
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $tanggungan->id,
            'nama' => '3 Anak',
            'nilai' => 3,
            'nilai_min' => 3,
            'nilai_max' => 3,
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $tanggungan->id,
            'nama' => '>3 Anak',
            'nilai' => 4,
            'nilai_min' => 4,
            'nilai_max' => 20,
        ]);

        // Kriteria Kondisi Rumah
        $kondisiRumah = Kriteria::create([
            'nama' => 'Kondisi Rumah',
            'deskripsi' => 'Kriteria berdasarkan kondisi fisik rumah',
            'tipe_kriteria' => 'kondisi_rumah'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $kondisiRumah->id,
            'nama' => 'Baik',
            'nilai' => 1,
            'nilai_min' => 1,
            'nilai_max' => 1,
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $kondisiRumah->id,
            'nama' => 'Cukup',
            'nilai' => 2,
            'nilai_min' => 2,
            'nilai_max' => 2,
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $kondisiRumah->id,
            'nama' => 'Kurang',
            'nilai' => 3,
            'nilai_min' => 3,
            'nilai_max' => 3,
        ]);

        // Kriteria Status Kepemilikan
        $statusKepemilikan = Kriteria::create([
            'nama' => 'Status Kepemilikan',
            'deskripsi' => 'Kriteria berdasarkan status kepemilikan rumah',
            'tipe_kriteria' => 'status_kepemilikan'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $statusKepemilikan->id,
            'nama' => 'Hak Milik',
            'nilai' => 1,
            'nilai_min' => 1,
            'nilai_max' => 1,
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $statusKepemilikan->id,
            'nama' => 'Numpang',
            'nilai' => 2,
            'nilai_min' => 2,
            'nilai_max' => 2,
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $statusKepemilikan->id,
            'nama' => 'Sewa',
            'nilai' => 3,
            'nilai_min' => 3,
            'nilai_max' => 3,
        ]);

        // Kriteria Penghasilan
        $penghasilan = Kriteria::create([
            'nama' => 'Penghasilan',
            'deskripsi' => 'Kriteria berdasarkan total penghasilan per bulan',
            'tipe_kriteria' => 'penghasilan'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $penghasilan->id,
            'nama' => '>4000000',
            'nilai' => 1,
            'nilai_min' => 4000001,
            'nilai_max' => 9999999,
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $penghasilan->id,
            'nama' => '3000001 - 4000000',
            'nilai' => 2,
            'nilai_min' => 3000001,
            'nilai_max' => 4000000,
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $penghasilan->id,
            'nama' => '2000001 - 3000000',
            'nilai' => 3,
            'nilai_min' => 2000001,
            'nilai_max' => 3000000,
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $penghasilan->id,
            'nama' => '1000000 - 2000000',
            'nilai' => 4,
            'nilai_min' => 1000000,
            'nilai_max' => 2000000,
        ]);
    }
} 