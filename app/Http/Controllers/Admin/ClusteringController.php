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
            // Ambil data penduduk
            $penduduks = Penduduk::all();
            
            if ($penduduks->isEmpty()) {
                return redirect()->back()->with('warning', 'Tidak ada data penduduk yang tersedia.');
            }

            // Inisialisasi centroid awal secara random
            $centroids = [];
            $randomPenduduks = $penduduks->random(3);
            
            foreach ($randomPenduduks as $index => $penduduk) {
                $centroids['C' . ($index + 1)] = [
                    'usia' => $penduduk->usia,
                    'tanggungan' => $penduduk->tanggungan,
                    'kondisi_rumah' => $penduduk->kondisi_rumah,
                    'status_kepemilikan' => $penduduk->status_kepemilikan,
                    'penghasilan' => $penduduk->penghasilan
                ];
            }

            $maxIterations = 100;
            $iteration = 0;
            $converged = false;
            $previousClusters = [];

            while (!$converged && $iteration < $maxIterations) {
                // Hitung jarak untuk setiap penduduk ke setiap centroid
                $distanceResults = [];
                $clusterAssignments = [];

                foreach ($penduduks as $penduduk) {
                    $distances = [];
                    foreach ($centroids as $cluster => $centroid) {
                        $distance = sqrt(
                            pow($penduduk->usia - $centroid['usia'], 2) +
                            pow($penduduk->tanggungan - $centroid['tanggungan'], 2) +
                            pow($penduduk->kondisi_rumah - $centroid['kondisi_rumah'], 2) +
                            pow($penduduk->status_kepemilikan - $centroid['status_kepemilikan'], 2) +
                            pow($penduduk->penghasilan - $centroid['penghasilan'], 2)
                        );
                        $distances[] = $distance;
                    }
                    
                    $minDistanceIndex = array_search(min($distances), $distances);
                    $cluster = 'C' . ($minDistanceIndex + 1);
                    $clusterAssignments[$penduduk->id] = $cluster;
                    
                    $distanceResults[] = [
                        'penduduk' => $penduduk,
                        'distances' => $distances,
                        'cluster' => $cluster
                    ];
                }

                // Cek konvergensi
                if ($previousClusters == $clusterAssignments) {
                    $converged = true;
                } else {
                    $previousClusters = $clusterAssignments;
                }

                // Update centroid baru
                $newCentroids = [
                    'C1' => ['usia' => 0, 'tanggungan' => 0, 'kondisi_rumah' => 0, 'status_kepemilikan' => 0, 'penghasilan' => 0, 'count' => 0],
                    'C2' => ['usia' => 0, 'tanggungan' => 0, 'kondisi_rumah' => 0, 'status_kepemilikan' => 0, 'penghasilan' => 0, 'count' => 0],
                    'C3' => ['usia' => 0, 'tanggungan' => 0, 'kondisi_rumah' => 0, 'status_kepemilikan' => 0, 'penghasilan' => 0, 'count' => 0]
                ];

                foreach ($penduduks as $penduduk) {
                    $cluster = $clusterAssignments[$penduduk->id];
                    $newCentroids[$cluster]['usia'] += $penduduk->usia;
                    $newCentroids[$cluster]['tanggungan'] += $penduduk->tanggungan;
                    $newCentroids[$cluster]['kondisi_rumah'] += $penduduk->kondisi_rumah;
                    $newCentroids[$cluster]['status_kepemilikan'] += $penduduk->status_kepemilikan;
                    $newCentroids[$cluster]['penghasilan'] += $penduduk->penghasilan;
                    $newCentroids[$cluster]['count']++;
                }

                // Hitung rata-rata untuk setiap centroid
                foreach ($newCentroids as $cluster => $centroid) {
                    if ($centroid['count'] > 0) {
                        $centroids[$cluster] = [
                            'usia' => $centroid['usia'] / $centroid['count'],
                            'tanggungan' => $centroid['tanggungan'] / $centroid['count'],
                            'kondisi_rumah' => $centroid['kondisi_rumah'] / $centroid['count'],
                            'status_kepemilikan' => $centroid['status_kepemilikan'] / $centroid['count'],
                            'penghasilan' => $centroid['penghasilan'] / $centroid['count']
                        ];
                    }
                }

                $iteration++;
            }

            // Update mapping di database
            foreach ($clusterAssignments as $pendudukId => $cluster) {
                MappingCentroid::updateOrCreate(
                    ['data_ke' => $pendudukId],
                    [
                        'cluster' => $cluster,
                        'usia' => $penduduks->find($pendudukId)->usia,
                        'jumlah_tanggungan' => $penduduks->find($pendudukId)->tanggungan,
                        'kondisi_rumah' => $penduduks->find($pendudukId)->kondisi_rumah,
                        'status_kepemilikan' => $penduduks->find($pendudukId)->status_kepemilikan,
                        'jumlah_penghasilan' => $penduduks->find($pendudukId)->penghasilan
                    ]
                );
            }

            // Simpan hasil ke session
            session(['distanceResults' => $distanceResults]);

            return redirect()->back()->with('success', 'Perhitungan clustering berhasil dilakukan dalam ' . $iteration . ' iterasi');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}