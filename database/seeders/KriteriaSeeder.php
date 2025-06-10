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
            'nama' => 'Tidak Membutuhkan',
            'nilai' => 1,
            'nilai_min' => 0,
            'nilai_max' => 39,
            'keterangan' => 'Usia < 40 tahun'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $usia->id,
            'nama' => 'Cukup Membutuhkan',
            'nilai' => 2,
            'nilai_min' => 40,
            'nilai_max' => 49,
            'keterangan' => 'Usia 40-49 tahun'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $usia->id,
            'nama' => 'Membutuhkan',
            'nilai' => 3,
            'nilai_min' => 50,
            'nilai_max' => 59,
            'keterangan' => 'Usia 50-59 tahun'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $usia->id,
            'nama' => 'Sangat Membutuhkan',
            'nilai' => 4,
            'nilai_min' => 60,
            'nilai_max' => 100,
            'keterangan' => 'Usia ≥ 60 tahun'
        ]);

        // Kriteria Jumlah Tanggungan
        $tanggungan = Kriteria::create([
            'nama' => 'Jumlah Tanggungan',
            'kode' => 'C2',
            'deskripsi' => 'Kriteria berdasarkan jumlah anggota keluarga yang ditanggung'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $tanggungan->id,
            'nama' => 'Tidak Membutuhkan',
            'nilai' => 1,
            'nilai_min' => 1,
            'nilai_max' => 2,
            'keterangan' => '1-2 orang'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $tanggungan->id,
            'nama' => 'Cukup Membutuhkan',
            'nilai' => 2,
            'nilai_min' => 3,
            'nilai_max' => 3,
            'keterangan' => '3 orang'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $tanggungan->id,
            'nama' => 'Membutuhkan',
            'nilai' => 3,
            'nilai_min' => 4,
            'nilai_max' => 4,
            'keterangan' => '4 orang'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $tanggungan->id,
            'nama' => 'Sangat Membutuhkan',
            'nilai' => 4,
            'nilai_min' => 5,
            'nilai_max' => 20,
            'keterangan' => '≥ 5 orang'
        ]);

        // Kriteria Kondisi Rumah
        $kondisiRumah = Kriteria::create([
            'nama' => 'Kondisi Rumah',
            'kode' => 'C3',
            'deskripsi' => 'Kriteria berdasarkan kondisi fisik rumah'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $kondisiRumah->id,
            'nama' => 'Tidak Membutuhkan',
            'nilai' => 1,
            'keterangan' => 'Sangat Baik'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $kondisiRumah->id,
            'nama' => 'Cukup Membutuhkan',
            'nilai' => 2,
            'keterangan' => 'Baik'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $kondisiRumah->id,
            'nama' => 'Membutuhkan',
            'nilai' => 3,
            'keterangan' => 'Kurang Baik'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $kondisiRumah->id,
            'nama' => 'Sangat Membutuhkan',
            'nilai' => 4,
            'keterangan' => 'Tidak Layak'
        ]);

        // Kriteria Status Kepemilikan
        $statusKepemilikan = Kriteria::create([
            'nama' => 'Status Kepemilikan',
            'kode' => 'C4',
            'deskripsi' => 'Kriteria berdasarkan status kepemilikan rumah'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $statusKepemilikan->id,
            'nama' => 'Tidak Membutuhkan',
            'nilai' => 1,
            'keterangan' => 'Milik Sendiri'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $statusKepemilikan->id,
            'nama' => 'Cukup Membutuhkan',
            'nilai' => 2,
            'keterangan' => 'Kontrak'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $statusKepemilikan->id,
            'nama' => 'Membutuhkan',
            'nilai' => 3,
            'keterangan' => 'Sewa'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $statusKepemilikan->id,
            'nama' => 'Sangat Membutuhkan',
            'nilai' => 4,
            'keterangan' => 'Menumpang'
        ]);

        // Kriteria Penghasilan
        $penghasilan = Kriteria::create([
            'nama' => 'Penghasilan',
            'kode' => 'C5',
            'deskripsi' => 'Kriteria berdasarkan total penghasilan per bulan'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $penghasilan->id,
            'nama' => 'Tidak Membutuhkan',
            'nilai' => 1,
            'nilai_min' => 3000001,
            'nilai_max' => 9999999,
            'keterangan' => '> Rp 3.000.000'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $penghasilan->id,
            'nama' => 'Cukup Membutuhkan',
            'nilai' => 2,
            'nilai_min' => 2000001,
            'nilai_max' => 3000000,
            'keterangan' => 'Rp 2.000.000 - Rp 3.000.000'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $penghasilan->id,
            'nama' => 'Membutuhkan',
            'nilai' => 3,
            'nilai_min' => 1000001,
            'nilai_max' => 2000000,
            'keterangan' => 'Rp 1.000.000 - Rp 2.000.000'
        ]);

        NilaiKriteria::create([
            'kriteria_id' => $penghasilan->id,
            'nama' => 'Sangat Membutuhkan',
            'nilai' => 4,
            'nilai_min' => 0,
            'nilai_max' => 1000000,
            'keterangan' => '≤ Rp 1.000.000'
        ]);
    }
} 