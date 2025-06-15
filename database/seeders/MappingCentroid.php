<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MappingCentroid;
use App\Models\Penduduk;
use App\Models\Centroid;
use Illuminate\Support\Facades\DB;

class MappingCentroidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mapping_centroids')->truncate(); // Clear existing data to prevent duplicates

        $penduduks = Penduduk::all();
        $centroids = Centroid::all();

        if ($penduduks->isEmpty() || $centroids->isEmpty()) {
            $this->command->warn('No Penduduk or Centroid data found. Skipping MappingCentroid seeding.');
            return;
        }

        foreach ($penduduks as $penduduk) {
            // Randomly assign a centroid for simplicity in dummy data
            $randomCentroid = $centroids->random();

            // Dummy values for jarak_euclidean, status_kelayakan, and keterangan
            // In a real application, jarak_euclidean would be calculated,
            // and status_kelayakan/keterangan would be determined by clustering logic.
            $jarakEuclidean = rand(10, 100) / 10; // Random float between 1.0 and 10.0

            $statusKelayakan = '';
            $keterangan = '';

            switch ($randomCentroid->cluster) {
                case 1:
                    $statusKelayakan = 'Layak';
                    $keterangan = 'Membutuhkan';
                    break;
                case 2:
                    $statusKelayakan = 'Tidak Layak';
                    $keterangan = 'Tidak Membutuhkan';
                    break;
                case 3:
                    $statusKelayakan = 'Layak';
                    $keterangan = 'Prioritas Sedang';
                    break;
                default:
                    $statusKelayakan = 'Unknown';
                    $keterangan = 'Unknown';
                    break;
            }

            MappingCentroid::create([
                'penduduk_id' => $penduduk->id,
                'centroid_id' => $randomCentroid->id,
                'jarak_euclidean' => $jarakEuclidean,
                'cluster' => $randomCentroid->cluster,
                'status_kelayakan' => $statusKelayakan,
                'keterangan' => $keterangan,
            ]);
        }

        $this->command->info(count($penduduks) . ' dummy mapping centroid records created.');
    }
} 