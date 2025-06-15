<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MappingCentroid;
use App\Models\Penduduk;
use App\Models\Centroid;
use Illuminate\Support\Facades\File;

class MappingCentroidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Path to your CSV file
        $csvFile = base_path('database/seeders/data/mapping_centroid.csv');

        // Check if the file exists
        if (!File::exists($csvFile)) {
            $this->command->error("CSV file not found: {$csvFile}");
            return;
        }

        $data = array_map('str_getcsv', file($csvFile));
        $header = array_shift($data); // Get the header row

        // Find the index for relevant columns
        $dataKeIndex = array_search('Data ke-', $header);
        $clusterIndex = array_search('Cluster', $header);

        if ($dataKeIndex === false || $clusterIndex === false) {
            $this->command->error("Required columns (Data ke-, Cluster) not found in CSV header.");
            return;
        }

        foreach ($data as $row) {
            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            $pendudukId = $row[$dataKeIndex];
            $clusterName = 'C' . $row[$clusterIndex]; // Convert '1' to 'C1', '2' to 'C2', etc.

            // Find Penduduk and Centroid by their IDs/names
            $penduduk = Penduduk::find($pendudukId);
            $centroid = Centroid::where('nama_centroid', $clusterName)->first();

            if ($penduduk && $centroid) {
                MappingCentroid::create([
                    'penduduk_id' => $penduduk->id,
                    'centroid_id' => $centroid->id,
                    'jarak_euclidean' => rand(1, 100) / 10, // Placeholder: Random float between 0.1 and 10.0
                    'cluster' => (int)$row[$clusterIndex],
                    'status_kelayakan' => ((int)$row[$clusterIndex] == 1) ? 'Layak' : 'Tidak Layak', // Placeholder: C1 is Layak
                    'keterangan' => 'Data seeded from CSV'
                ]);
                $this->command->info("Mapped Penduduk ID {$pendudukId} to {$clusterName}");
            } else {
                $this->command->warn("Skipping row: Penduduk ID {$pendudukId} or Centroid {$clusterName} not found.");
            }
        }

        $this->command->info("Mapping Centroid seeding completed.");
    }
}
