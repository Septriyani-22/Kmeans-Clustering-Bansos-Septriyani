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
            // Validate input
            $request->validate([
                'jumlah_cluster' => 'required|integer|min:2|max:10'
            ]);

            $jumlahCluster = $request->jumlah_cluster;

            // Get all penduduk data
            $penduduks = Penduduk::all();
            if ($penduduks->isEmpty()) {
                return redirect()->route('admin.clustering.index')
                    ->with('error', 'Tidak ada data penduduk yang tersedia.');
            }

            // Delete existing centroids and results
            HasilKmeans::query()->delete();
            Centroid::query()->delete();

            // Initialize centroids with random penduduk data
            $randomPenduduk = Penduduk::inRandomOrder()->take($jumlahCluster)->get();
            
            if ($randomPenduduk->count() < $jumlahCluster) {
                return redirect()->route('admin.clustering.index')
                    ->with('error', 'Jumlah data penduduk tidak mencukupi untuk jumlah cluster yang diminta.');
            }

            $centroids = [];
            foreach ($randomPenduduk as $index => $p) {
                $centroid = Centroid::create([
                    'nama_centroid' => 'C' . ($index + 1),
                    'usia' => (int)$p->usia,
                    'tanggungan_num' => $this->convertToNumeric($p->tanggungan),
                    'kondisi_rumah' => $p->kondisi_rumah,
                    'status_kepemilikan' => $p->status_kepemilikan,
                    'penghasilan_num' => $this->convertToNumeric($p->penghasilan),
                    'tahun' => $p->tahun,
                    'periode' => 1
                ]);
                $centroids[] = $centroid;
            }

            // K-means clustering process
            $maxIterations = 100;
            $iteration = 0;
            $converged = false;

            while (!$converged && $iteration < $maxIterations) {
                $iteration++;
                $clusterAssignments = [];
                $clusterSums = array_fill(0, $jumlahCluster, [
                    'usia' => 0, 'tanggungan' => 0, 'kondisi_rumah' => 0,
                    'status_kepemilikan' => 0, 'penghasilan' => 0, 'count' => 0
                ]);

                // Assign each penduduk to nearest centroid
                foreach ($penduduks as $penduduk) {
                    $minDistance = PHP_FLOAT_MAX;
                    $nearestCluster = 0;

                    foreach ($centroids as $index => $centroid) {
                        $distance = sqrt(
                            pow($penduduk->usia - $centroid->usia, 2) +
                            pow($this->convertToNumeric($penduduk->tanggungan) - $centroid->tanggungan_num, 2) +
                            pow($this->convertKondisiRumah($penduduk->kondisi_rumah) - $this->convertKondisiRumah($centroid->kondisi_rumah), 2) +
                            pow($this->convertStatusKepemilikan($penduduk->status_kepemilikan) - $this->convertStatusKepemilikan($centroid->status_kepemilikan), 2) +
                            pow($this->convertToNumeric($penduduk->penghasilan) - $centroid->penghasilan_num, 2)
                        );

                        if ($distance < $minDistance) {
                            $minDistance = $distance;
                            $nearestCluster = $index;
                        }
                    }

                    $clusterAssignments[$penduduk->id] = $nearestCluster;
                    $clusterSums[$nearestCluster]['usia'] += $penduduk->usia;
                    $clusterSums[$nearestCluster]['tanggungan'] += $this->convertToNumeric($penduduk->tanggungan);
                    $clusterSums[$nearestCluster]['kondisi_rumah'] += $this->convertKondisiRumah($penduduk->kondisi_rumah);
                    $clusterSums[$nearestCluster]['status_kepemilikan'] += $this->convertStatusKepemilikan($penduduk->status_kepemilikan);
                    $clusterSums[$nearestCluster]['penghasilan'] += $this->convertToNumeric($penduduk->penghasilan);
                    $clusterSums[$nearestCluster]['count']++;
                }

                // Update centroids
                $centroidsMoved = false;
                foreach ($centroids as $index => $centroid) {
                    if ($clusterSums[$index]['count'] > 0) {
                        $newUsia = $clusterSums[$index]['usia'] / $clusterSums[$index]['count'];
                        $newTanggungan = $clusterSums[$index]['tanggungan'] / $clusterSums[$index]['count'];
                        $newKondisiRumah = $clusterSums[$index]['kondisi_rumah'] / $clusterSums[$index]['count'];
                        $newStatusKepemilikan = $clusterSums[$index]['status_kepemilikan'] / $clusterSums[$index]['count'];
                        $newPenghasilan = $clusterSums[$index]['penghasilan'] / $clusterSums[$index]['count'];

                        if ($newUsia != $centroid->usia || 
                            $newTanggungan != $centroid->tanggungan_num || 
                            $newKondisiRumah != $this->convertKondisiRumah($centroid->kondisi_rumah) || 
                            $newStatusKepemilikan != $this->convertStatusKepemilikan($centroid->status_kepemilikan) || 
                            $newPenghasilan != $centroid->penghasilan_num) {
                            $centroidsMoved = true;
                        }

                        $centroid->update([
                            'usia' => (int)$newUsia,
                            'tanggungan_num' => $newTanggungan,
                            'kondisi_rumah' => $this->reverseConvertKondisiRumah($newKondisiRumah),
                            'status_kepemilikan' => $this->reverseConvertStatusKepemilikan($newStatusKepemilikan),
                            'penghasilan_num' => $newPenghasilan
                        ]);
                    }
                }

                if (!$centroidsMoved) {
                    $converged = true;
                }
            }

            // Save clustering results
            foreach ($clusterAssignments as $pendudukId => $clusterIndex) {
                $penduduk = Penduduk::find($pendudukId);
                $centroid = $centroids[$clusterIndex];

                HasilKmeans::create([
                    'penduduk_id' => $pendudukId,
                    'centroid_id' => $centroid->id,
                    'cluster' => $clusterIndex + 1,
                    'jarak' => sqrt(
                        pow($penduduk->usia - $centroid->usia, 2) +
                        pow($this->convertToNumeric($penduduk->tanggungan) - $centroid->tanggungan_num, 2) +
                        pow($this->convertKondisiRumah($penduduk->kondisi_rumah) - $this->convertKondisiRumah($centroid->kondisi_rumah), 2) +
                        pow($this->convertStatusKepemilikan($penduduk->status_kepemilikan) - $this->convertStatusKepemilikan($centroid->status_kepemilikan), 2) +
                        pow($this->convertToNumeric($penduduk->penghasilan) - $centroid->penghasilan_num, 2)
                    ),
                    'iterasi' => $iteration,
                    'tahun' => $penduduk->tahun,
                    'periode' => 1
                ]);
            }

            return redirect()->route('admin.centroid.index')
                ->with('success', 'Proses clustering berhasil dilakukan.');

        } catch (\Exception $e) {
            \Log::error('Clustering error: ' . $e->getMessage());
            return redirect()->route('admin.clustering.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function initializeCentroids($jumlahCluster)
    {
        // Get random penduduk records for initial centroids
        $randomPenduduk = Penduduk::inRandomOrder()->take($jumlahCluster)->get();
        
        foreach ($randomPenduduk as $index => $p) {
            Centroid::create([
                'nama_centroid' => 'C' . ($index + 1),
                'usia' => $p->usia,
                'tanggungan_num' => $p->tanggungan_num,
                'kondisi_rumah' => $p->kondisi_rumah,
                'status_kepemilikan' => $p->status_kepemilikan,
                'penghasilan_num' => $p->penghasilan_num,
                'tahun' => $p->tahun,
                'periode' => 1
            ]);
        }
    }

    public function reset()
    {
        try {
            // Delete all clustering results first (child table)
            HasilKmeans::query()->delete();
            
            // Then delete all centroids (parent table)
            Centroid::query()->delete();
            
            return redirect()->route('admin.clustering.index')
                ->with('success', 'Semua data clustering telah direset.');
        } catch (\Exception $e) {
            return redirect()->route('admin.clustering.index')
                ->with('error', 'Gagal mereset data: ' . $e->getMessage());
        }
    }

    private function convertKondisiRumah($value)
    {
        return match(strtolower($value)) {
            'sangat baik' => 4,
            'baik' => 3,
            'cukup' => 2,
            'buruk' => 1,
            default => 2
        };
    }

    private function reverseConvertKondisiRumah($value)
    {
        return match((int)$value) {
            4 => 'sangat baik',
            3 => 'baik',
            2 => 'cukup',
            1 => 'buruk',
            default => 'cukup'
        };
    }

    private function convertStatusKepemilikan($value)
    {
        return match(strtolower($value)) {
            'hak milik' => 3,
            'sewa' => 2,
            'kontrak' => 1,
            default => 1
        };
    }

    private function reverseConvertStatusKepemilikan($value)
    {
        return match((int)$value) {
            3 => 'hak milik',
            2 => 'sewa',
            1 => 'kontrak',
            default => 'kontrak'
        };
    }

    private function convertToNumeric($value)
    {
        if (is_numeric($value)) {
            return (float)$value;
        }
        // Remove any non-numeric characters except decimal point
        $numeric = preg_replace('/[^0-9.]/', '', $value);
        return $numeric ? (float)$numeric : 0;
    }
}