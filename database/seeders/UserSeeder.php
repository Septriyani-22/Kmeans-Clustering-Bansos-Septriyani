<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run()
    {
        $file = database_path('seeders/data/dummy_users.csv');
        if (!file_exists($file) || !is_readable($file)) {
            $this->command->error("File dummy_users.csv tidak ditemukan atau tidak bisa dibaca.");
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
                    'name' => $row[1],
                    'username' => $row[2],
                    'email' => $row[3],
                    'password' => Hash::make($row[4]),
                    'role' => $row[5],
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            fclose($handle);
        }
        if (!empty($data)) {
            DB::table('users')->insert($data);
            $this->command->info(count($data) . ' user berhasil diimport.');
        } else {
            $this->command->warn('Tidak ada data user untuk diimport.');
        }
    }
}
