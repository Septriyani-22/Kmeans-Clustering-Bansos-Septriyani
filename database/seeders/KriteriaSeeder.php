<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KriteriaSeeder extends Seeder
{
    public function run(): void
    {
        $file = database_path('seeders/data/dummy_kriteria.csv');
        if (!file_exists($file) || !is_readable($file)) {
            $this->command->error("File dummy_kriteria.csv tidak ditemukan atau tidak bisa dibaca.");
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
                $data[] = [
                    'nama_kriteria' => $row[1],
                    'nilai' => (int)$row[2],
                    'keterangan' => $row[3],
                    'is_aktif' => (int)$row[4],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            fclose($handle);
        }

        if (!empty($data)) {
            DB::table('kriteria')->insert($data);
            $this->command->info(count($data) . ' data kriteria berhasil diimport.');
        } else {
            $this->command->warn('Tidak ada data kriteria untuk diimport.');
        }
    }
} 