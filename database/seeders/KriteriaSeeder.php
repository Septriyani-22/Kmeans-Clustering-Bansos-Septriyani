<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kriteria;
use App\Models\NilaiKriteria;

class KriteriaSeeder extends Seeder
{
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('nilai_kriteria')->truncate();
        \DB::table('kriteria')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Kriteria Usia
        $usia = Kriteria::create([
            'nama' => 'Usia',
            'kode' => 'C1',
            'deskripsi' => 'Kriteria berdasarkan usia kepala keluarga'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $usia->id,
            'nama' => '15-25 Tahun',
            'nilai' => 1,
            'nilai_min' => 15,
            'nilai_max' => 25,
            'keterangan' => 'Usia 15-25 tahun'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $usia->id,
            'nama' => '25-35 Tahun',
            'nilai' => 2,
            'nilai_min' => 25,
            'nilai_max' => 35,
            'keterangan' => 'Usia 25-35 tahun'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $usia->id,
            'nama' => '36-45 Tahun',
            'nilai' => 3,
            'nilai_min' => 36,
            'nilai_max' => 45,
            'keterangan' => 'Usia 36-45 tahun'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $usia->id,
            'nama' => '>46 Tahun',
            'nilai' => 4,
            'nilai_min' => 46,
            'nilai_max' => 100,
            'keterangan' => 'Usia >46 tahun'
        ]);

        // Kriteria Jumlah Tanggungan
        $tanggungan = Kriteria::create([
            'nama' => 'Jumlah Tanggungan',
            'kode' => 'C2',
            'deskripsi' => 'Kriteria berdasarkan jumlah anggota keluarga yang ditanggung'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $tanggungan->id,
            'nama' => '1 Anak',
            'nilai' => 1,
            'nilai_min' => 1,
            'nilai_max' => 1,
            'keterangan' => '1 anak'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $tanggungan->id,
            'nama' => '2 Anak',
            'nilai' => 2,
            'nilai_min' => 2,
            'nilai_max' => 2,
            'keterangan' => '2 anak'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $tanggungan->id,
            'nama' => '3 Anak',
            'nilai' => 3,
            'nilai_min' => 3,
            'nilai_max' => 3,
            'keterangan' => '3 anak'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $tanggungan->id,
            'nama' => '>3 Anak',
            'nilai' => 4,
            'nilai_min' => 4,
            'nilai_max' => 20,
            'keterangan' => '>3 anak'
        ]);

        // Kriteria Kondisi Rumah
        $kondisiRumah = Kriteria::create([
            'nama' => 'Kondisi Rumah',
            'kode' => 'C3',
            'deskripsi' => 'Kriteria berdasarkan kondisi fisik rumah'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $kondisiRumah->id,
            'nama' => 'Baik',
            'nilai' => 1,
            'nilai_min' => 1,
            'nilai_max' => 1,
            'keterangan' => 'Kondisi rumah baik'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $kondisiRumah->id,
            'nama' => 'Cukup',
            'nilai' => 2,
            'nilai_min' => 2,
            'nilai_max' => 2,
            'keterangan' => 'Kondisi rumah cukup'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $kondisiRumah->id,
            'nama' => 'Kurang',
            'nilai' => 3,
            'nilai_min' => 3,
            'nilai_max' => 3,
            'keterangan' => 'Kondisi rumah kurang'
        ]);

        // Kriteria Status Kepemilikan
        $statusKepemilikan = Kriteria::create([
            'nama' => 'Status Kepemilikan',
            'kode' => 'C4',
            'deskripsi' => 'Kriteria berdasarkan status kepemilikan rumah'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $statusKepemilikan->id,
            'nama' => 'Hak Milik',
            'nilai' => 1,
            'nilai_min' => 1,
            'nilai_max' => 1,
            'keterangan' => 'Status kepemilikan hak milik'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $statusKepemilikan->id,
            'nama' => 'Numpang',
            'nilai' => 2,
            'nilai_min' => 2,
            'nilai_max' => 2,
            'keterangan' => 'Status kepemilikan numpang'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $statusKepemilikan->id,
            'nama' => 'Sewa',
            'nilai' => 3,
            'nilai_min' => 3,
            'nilai_max' => 3,
            'keterangan' => 'Status kepemilikan sewa'
        ]);

        // Kriteria Penghasilan
        $penghasilan = Kriteria::create([
            'nama' => 'Penghasilan',
            'kode' => 'C5',
            'deskripsi' => 'Kriteria berdasarkan total penghasilan per bulan'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $penghasilan->id,
            'nama' => '>4000000',
            'nilai' => 1,
            'nilai_min' => 4000000,
            'nilai_max' => 9999999,
            'keterangan' => 'Penghasilan > Rp 4.000.000'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $penghasilan->id,
            'nama' => '3000000 - 4000000',
            'nilai' => 2,
            'nilai_min' => 3000000,
            'nilai_max' => 4000000,
            'keterangan' => 'Penghasilan Rp 3.000.000 - Rp 4.000.000'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $penghasilan->id,
            'nama' => '2000000 - 3000000',
            'nilai' => 3,
            'nilai_min' => 2000000,
            'nilai_max' => 3000000,
            'keterangan' => 'Penghasilan Rp 2.000.000 - Rp 3.000.000'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $penghasilan->id,
            'nama' => '1000000 - 2000000',
            'nilai' => 4,
            'nilai_min' => 1000000,
            'nilai_max' => 2000000,
            'keterangan' => 'Penghasilan Rp 1.000.000 - Rp 2.000.000'
        ]);
    }
} 