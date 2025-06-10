<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penduduk;
use App\Models\HasilClustering;
use App\Models\Iterasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClusteringController extends Controller
{
    public function index()
    {
        $hasil_clustering = HasilClustering::with('penduduk')->get();
        return view('admin.clustering.index', compact('hasil_clustering'));
    }

    public function process(Request $request)
    {
        try {
            DB::beginTransaction();

            // Hapus data clustering sebelumnya
            HasilClustering::truncate();
            Iterasi::truncate();

            // Ambil data penduduk
            $penduduk = Penduduk::all();
            if ($penduduk->isEmpty()) {
                throw new \Exception('Tidak ada data penduduk yang dapat diproses.');
            }

            // Inisialisasi centroid awal
            $centroids = $this->initializeCentroids($penduduk, 3);

            // Proses clustering
            $iterasi = 0;
            $maxIterasi = 100;
            $converged = false;

            while (!$converged && $iterasi < $maxIterasi) {
                $iterasi++;
                
                // Hitung jarak dan tentukan cluster
                foreach ($penduduk as $p) {
                    $minDistance = PHP_FLOAT_MAX;
                    $cluster = 1;

                    foreach ($centroids as $index => $centroid) {
                        $distance = $this->calculateDistance($p, $centroid);
                        if ($distance < $minDistance) {
                            $minDistance = $distance;
                            $cluster = $index + 1;
                        }
                    }

                    // Simpan hasil clustering
                    HasilClustering::create([
                        'penduduk_id' => $p->id,
                        'cluster' => $cluster
                    ]);
                }

                // Update centroid
                $newCentroids = $this->updateCentroids($penduduk, 3);
                
                // Cek konvergensi
                $converged = $this->checkConvergence($centroids, $newCentroids);
                
                if (!$converged) {
                    $centroids = $newCentroids;
                }
            }

            // Simpan informasi iterasi
            Iterasi::create([
                'iterasi' => $iterasi,
                'converged' => $converged
            ]);

            DB::commit();

            return redirect()->route('admin.clustering.index')
                ->with('success', 'Proses clustering berhasil dilakukan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.clustering.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function initializeCentroids($penduduk, $k)
    {
        $centroids = [];
        $count = $penduduk->count();
        
        // Pilih k data secara acak sebagai centroid awal
        $randomIndices = array_rand($penduduk->toArray(), $k);
        
        foreach ($randomIndices as $index) {
            $p = $penduduk[$index];
            $centroids[] = [
                'usia' => $p->usia,
                'tanggungan' => $p->tanggungan,
                'kondisi_rumah' => $p->kondisi_rumah,
                'status_kepemilikan' => $p->status_kepemilikan,
                'penghasilan' => $p->penghasilan
            ];
        }
        
        return $centroids;
    }

    private function calculateDistance($penduduk, $centroid)
    {
        $usiaDiff = pow($penduduk->usia - $centroid['usia'], 2);
        $tanggunganDiff = pow($penduduk->tanggungan - $centroid['tanggungan'], 2);
        $kondisiRumahDiff = pow($penduduk->kondisi_rumah - $centroid['kondisi_rumah'], 2);
        $statusKepemilikanDiff = pow($penduduk->status_kepemilikan - $centroid['status_kepemilikan'], 2);
        $penghasilanDiff = pow($penduduk->penghasilan - $centroid['penghasilan'], 2);

        return sqrt($usiaDiff + $tanggunganDiff + $kondisiRumahDiff + $statusKepemilikanDiff + $penghasilanDiff);
    }

    private function updateCentroids($penduduk, $k)
    {
        $newCentroids = array_fill(0, $k, [
            'usia' => 0,
            'tanggungan' => 0,
            'kondisi_rumah' => 0,
            'status_kepemilikan' => 0,
            'penghasilan' => 0
        ]);
        
        $clusterCounts = array_fill(0, $k, 0);

        foreach ($penduduk as $p) {
            $cluster = HasilClustering::where('penduduk_id', $p->id)->first()->cluster - 1;
            
            $newCentroids[$cluster]['usia'] += $p->usia;
            $newCentroids[$cluster]['tanggungan'] += $p->tanggungan;
            $newCentroids[$cluster]['kondisi_rumah'] += $p->kondisi_rumah;
            $newCentroids[$cluster]['status_kepemilikan'] += $p->status_kepemilikan;
            $newCentroids[$cluster]['penghasilan'] += $p->penghasilan;
            
            $clusterCounts[$cluster]++;
        }

        // Hitung rata-rata
        for ($i = 0; $i < $k; $i++) {
            if ($clusterCounts[$i] > 0) {
                $newCentroids[$i]['usia'] /= $clusterCounts[$i];
                $newCentroids[$i]['tanggungan'] /= $clusterCounts[$i];
                $newCentroids[$i]['kondisi_rumah'] /= $clusterCounts[$i];
                $newCentroids[$i]['status_kepemilikan'] /= $clusterCounts[$i];
                $newCentroids[$i]['penghasilan'] /= $clusterCounts[$i];
            }
        }

        return $newCentroids;
    }

    private function checkConvergence($oldCentroids, $newCentroids)
    {
        $threshold = 0.0001;
        
        for ($i = 0; $i < count($oldCentroids); $i++) {
            if (abs($oldCentroids[$i]['usia'] - $newCentroids[$i]['usia']) > $threshold ||
                abs($oldCentroids[$i]['tanggungan'] - $newCentroids[$i]['tanggungan']) > $threshold ||
                abs($oldCentroids[$i]['kondisi_rumah'] - $newCentroids[$i]['kondisi_rumah']) > $threshold ||
                abs($oldCentroids[$i]['status_kepemilikan'] - $newCentroids[$i]['status_kepemilikan']) > $threshold ||
                abs($oldCentroids[$i]['penghasilan'] - $newCentroids[$i]['penghasilan']) > $threshold) {
                return false;
            }
        }
        
        return true;
    }

    public function reset()
    {
        try {
            DB::beginTransaction();
            
            HasilClustering::truncate();
            Iterasi::truncate();
            
            DB::commit();
            
            return redirect()->route('admin.clustering.index')
                ->with('success', 'Data clustering berhasil direset.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.clustering.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}