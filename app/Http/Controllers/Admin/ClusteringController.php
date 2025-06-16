<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penduduk;
use App\Models\Centroid;
use App\Models\Iterasi;
use App\Models\HasilKmeans;
use App\Models\Kriteria;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\MappingCentroid;

class ClusteringController extends Controller
{
    public function index()
    {
        $penduduk = Penduduk::all();
        $centroids = Centroid::all();
        $iterasi = Iterasi::all();
        $hasilKmeans = HasilKmeans::with(['penduduk', 'centroid'])->get();
        
        return view('admin.clustering.index', compact('penduduk', 'centroids', 'iterasi', 'hasilKmeans'));
    }

    public function proses(Request $request)
    {
        try {
            $request->validate([
                'jumlah_cluster' => 'required|integer|min:2|max:10'
            ]);

            // Reset data sebelumnya
            HasilKmeans::query()->delete();
            Centroid::query()->delete();
            MappingCentroid::query()->delete();

            $penduduks = Penduduk::all();
            if ($penduduks->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada data penduduk yang tersedia.');
            }

            $kriteria = Kriteria::all();
            if ($kriteria->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada kriteria yang tersedia.');
            }

            // Inisialisasi centroid awal dengan data yang sudah ditentukan
            $initialCentroids = [
                [
                    'usia' => 4,
                    'tanggungan_num' => 3,
                    'kondisi_rumah' => 3,
                    'status_kepemilikan' => 2,
                    'penghasilan_num' => 4,
                    'tahun' => date('Y'),
                    'periode' => 1
                ],
                [
                    'usia' => 4,
                    'tanggungan_num' => 4,
                    'kondisi_rumah' => 2,
                    'status_kepemilikan' => 1,
                    'penghasilan_num' => 4,
                    'tahun' => date('Y'),
                    'periode' => 1
                ],
                [
                    'usia' => 4,
                    'tanggungan_num' => 3,
                    'kondisi_rumah' => 1,
                    'status_kepemilikan' => 1,
                    'penghasilan_num' => 2,
                    'tahun' => date('Y'),
                    'periode' => 1
                ]
            ];

            // Simpan centroid awal
            foreach ($initialCentroids as $centroid) {
                Centroid::create($centroid);
            }

            $centroidController = app(CentroidController::class);
            return $centroidController->calculateDistances();

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function reset()
    {
        try {
            // Nonaktifkan foreign key checks sementara
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            
            // Hapus semua data hasil perhitungan
            DB::table('hasil_kmeans')->truncate();
            DB::table('mapping_centroids')->truncate();
            DB::table('centroids')->truncate();
            DB::table('iterasi')->truncate();
            
            // Aktifkan kembali foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            
            // Hapus data dari session
            session()->forget(['distanceResults', 'centroids', 'iterasi', 'hasilKmeans']);
            
            return redirect()->route('admin.clustering.index')
                ->with('success', 'Semua data perhitungan berhasil direset');
        } catch (\Exception $e) {
            Log::error('Error resetting calculations: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mereset data: ' . $e->getMessage());
        }
    }

    public function calculate()
    {
        try {
            // Ambil data penduduk yang sudah di-mapping
            $mappings = MappingCentroid::with('penduduk')->get();
            
            if ($mappings->isEmpty()) {
                return redirect()->back()->with('warning', 'Tidak ada data mapping yang tersedia. Silakan tambahkan mapping terlebih dahulu.');
            }

            // Kelompokkan data berdasarkan cluster
            $clusterData = [
                'C1' => [],
                'C2' => [],
                'C3' => []
            ];

            foreach ($mappings as $mapping) {
                $clusterData[$mapping->cluster][] = [
                    'usia' => $mapping->usia,
                    'jumlah_tanggungan' => $mapping->jumlah_tanggungan,
                    'kondisi_rumah' => $mapping->kondisi_rumah,
                    'status_kepemilikan' => $mapping->status_kepemilikan,
                    'jumlah_penghasilan' => $mapping->jumlah_penghasilan
                ];
            }

            // Hitung centroid baru untuk setiap cluster
            $newCentroids = [];
            foreach ($clusterData as $cluster => $data) {
                if (!empty($data)) {
                    $newCentroids[$cluster] = [
                        'usia' => array_sum(array_column($data, 'usia')) / count($data),
                        'jumlah_tanggungan' => array_sum(array_column($data, 'jumlah_tanggungan')) / count($data),
                        'kondisi_rumah' => array_sum(array_column($data, 'kondisi_rumah')) / count($data),
                        'status_kepemilikan' => array_sum(array_column($data, 'status_kepemilikan')) / count($data),
                        'jumlah_penghasilan' => array_sum(array_column($data, 'jumlah_penghasilan')) / count($data)
                    ];
                }
            }

            // Hitung jarak untuk setiap penduduk ke setiap centroid
            $distanceResults = [];
            $penduduks = Penduduk::all();

            foreach ($penduduks as $penduduk) {
                $distances = [];
                foreach ($newCentroids as $cluster => $centroid) {
                    $distance = sqrt(
                        pow($penduduk->usia - $centroid['usia'], 2) +
                        pow($penduduk->jumlah_tanggungan - $centroid['jumlah_tanggungan'], 2) +
                        pow($penduduk->kondisi_rumah - $centroid['kondisi_rumah'], 2) +
                        pow($penduduk->status_kepemilikan - $centroid['status_kepemilikan'], 2) +
                        pow($penduduk->jumlah_penghasilan - $centroid['jumlah_penghasilan'], 2)
                    );
                    $distances[] = $distance;
                }
                $distanceResults[] = [
                    'penduduk' => $penduduk,
                    'distances' => $distances
                ];
            }

            // Update cluster berdasarkan jarak terdekat
            foreach ($distanceResults as $result) {
                $minDistanceIndex = array_search(min($result['distances']), $result['distances']);
                $newCluster = 'C' . ($minDistanceIndex + 1);
                
                // Update mapping jika cluster berubah
                $mapping = MappingCentroid::where('data_ke', $result['penduduk']->id)->first();
                if ($mapping && $mapping->cluster !== $newCluster) {
                    $mapping->update(['cluster' => $newCluster]);
                }
            }

            return redirect()->back()->with('success', 'Perhitungan clustering berhasil dilakukan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}