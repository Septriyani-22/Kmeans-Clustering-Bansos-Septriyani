<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Centroid;
use Illuminate\Support\Facades\File;

class CentroidSeeder extends Seeder
{
    public function run()
    {
        $csvFile = database_path('seeders/data/dummy_centroid.csv');
        
        if (!File::exists($csvFile)) {
            $this->command->error('File CSV tidak ditemukan: ' . $csvFile);
            return;
        }

        $file = fopen($csvFile, 'r');
        $header = fgetcsv($file); // Skip header row

        while (($row = fgetcsv($file)) !== false) {
            $data = array_combine($header, $row);
            
            Centroid::create([
                'nama_centroid' => $data['nama_centroid'],
                'penghasilan_num' => (float) $data['penghasilan'],
                'tanggungan_num' => (int) $data['tanggungan'],
                'tahun' => (int) $data['tahun'],
                'periode' => (int) $data['periode'],
                'keterangan' => $data['keterangan']
            ]);
        }

        fclose($file);
    }
} 