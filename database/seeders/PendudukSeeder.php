<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penduduk;
use Illuminate\Support\Facades\DB;

class PendudukSeeder extends Seeder
{
    public function run()
    {
        $file = database_path('seeders/data/data_masukan.csv');
        
        if (!file_exists($file) || !is_readable($file)) {
            $this->command->error("File data_masukan.csv tidak ditemukan atau tidak bisa dibaca.");
            return;
        }

        $header = null;
        $data = [];

        if (($handle = fopen($file, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (!$header) {
                    $header = $row;
                    continue;
                }

                // Mapping sesuai data_masukan.csv:
                // NO,NIK,Nama,Tahun,Jenis Kelamin,Usia,Rt,Tanggungan ,Kondisi Rumah,Status Kepemilikan,Penghasilan
                $data[] = [
                    'nik' => $row[1],
                    'nama' => $row[2],
                    'tahun' => $row[3],
                    'jenis_kelamin' => $row[4],
                    'usia' => (int)$row[5],
                    'rt' => (int)$row[6],
                    'tanggungan' => (int)trim($row[7]),
                    'kondisi_rumah' => trim($row[8]),
                    'status_kepemilikan' => trim($row[9]),
                    'penghasilan' => (float)$row[10],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            fclose($handle);
        }

        // Insert ke DB (gunakan insert batch supaya cepat)
        if (!empty($data)) {
            Penduduk::insert($data);
            $this->command->info(count($data) . ' data penduduk berhasil diimport.');
        } else {
            $this->command->warn('Tidak ada data untuk diimport.');
        }
    }
}
