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

class ClusteringController extends Controller
{
    public function index()
    {
        $penduduk = Penduduk::all();
        $centroids = Centroid::all();
        $iterasi = Iterasi::orderBy('iterasi', 'desc')->first();
        $hasilKmeans = HasilKmeans::with(['penduduk', 'centroid'])->get();
        
        return view('admin.clustering.index', compact('penduduk', 'centroids', 'iterasi', 'hasilKmeans'));
    }

    public function proses(Request $request)
    {
        // Validasi input
        $request->validate([
            'tahun' => 'required|integer',
            'periode' => 'required|integer',
            'jumlah_cluster' => 'required|integer|min:2|max:5'
        ]);

        // Hapus data iterasi dan hasil kmeans yang ada
        Iterasi::truncate();
        HasilKmeans::truncate();

        // Ambil data penduduk dan kriteria
        $penduduk = Penduduk::where('tahun', $request->tahun)->get();
        $kriteria = Kriteria::all();

        if ($penduduk->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data penduduk untuk tahun yang dipilih');
        }

        if ($kriteria->isEmpty()) {
            return redirect()->back()->with('error', 'Data kriteria belum tersedia');
        }

        // Inisialisasi centroid awal
        $centroids = Centroid::where('tahun', $request->tahun)
                           ->where('periode', $request->periode)
                           ->get();

        if ($centroids->isEmpty()) {
            return redirect()->back()->with('error', 'Data centroid belum tersedia');
        }

        $maxIterasi = 100;
        $iterasi = 0;
        $konvergen = false;

        while (!$konvergen && $iterasi < $maxIterasi) {
            $iterasi++;
            
            // Simpan iterasi
            Iterasi::create([
                'iterasi' => $iterasi,
                'tahun' => $request->tahun,
                'periode' => $request->periode
            ]);

            // Hitung jarak dan tentukan cluster
            foreach ($penduduk as $p) {
                $minJarak = PHP_FLOAT_MAX;
                $clusterTerdekat = null;

                foreach ($centroids as $c) {
                    $jarak = $this->calculateDistance($p, $c, $kriteria);
                    
                    if ($jarak < $minJarak) {
                        $minJarak = $jarak;
                        $clusterTerdekat = $c;
                    }
                }

                // Simpan hasil clustering
                HasilKmeans::create([
                    'penduduk_id' => $p->id,
                    'centroid_id' => $clusterTerdekat->id,
                    'jarak' => $minJarak,
                    'iterasi' => $iterasi,
                    'tahun' => $request->tahun,
                    'periode' => $request->periode
                ]);
            }

            // Update centroid
            $konvergen = $this->updateCentroids($centroids, $kriteria);
        }

        return redirect()->route('admin.clustering.index')
                        ->with('success', 'Proses clustering selesai dengan ' . $iterasi . ' iterasi');
    }

    private function calculateDistance($penduduk, $centroid, $kriteria)
    {
        $totalJarak = 0;
        
        foreach ($kriteria as $k) {
            $nilaiPenduduk = 0;
            $nilaiCentroid = 0;
            
            switch ($k->nama_kriteria) {
                case 'Usia':
                    $nilaiPenduduk = (float) $penduduk->usia;
                    $nilaiCentroid = (float) $centroid->usia;
                    break;
                case 'Jumlah Tanggungan':
                    $nilaiPenduduk = (float) $penduduk->tanggungan;
                    $nilaiCentroid = (float) $centroid->tanggungan_num;
                    break;
                case 'Kondisi Rumah':
                    $nilaiPenduduk = $this->convertKondisiRumah($penduduk->kondisi_rumah);
                    $nilaiCentroid = $this->convertKondisiRumah($centroid->kondisi_rumah);
                    break;
                case 'Status Kepemilikan':
                    $nilaiPenduduk = $this->convertStatusKepemilikan($penduduk->status_kepemilikan);
                    $nilaiCentroid = $this->convertStatusKepemilikan($centroid->status_kepemilikan);
                    break;
                case 'Penghasilan':
                    $nilaiPenduduk = (float) $penduduk->penghasilan;
                    $nilaiCentroid = (float) $centroid->penghasilan_num;
                    break;
            }
            
            // Normalisasi nilai berdasarkan min dan max kriteria
            $nilaiPenduduk = ($nilaiPenduduk - $k->nilai_min) / ($k->nilai_max - $k->nilai_min);
            $nilaiCentroid = ($nilaiCentroid - $k->nilai_min) / ($k->nilai_max - $k->nilai_min);
            
            // Hitung jarak Euclidean
            $totalJarak += pow($nilaiPenduduk - $nilaiCentroid, 2);
        }
        
        return sqrt($totalJarak);
    }

    private function updateCentroids($centroids, $kriteria)
    {
        $konvergen = true;
        
        foreach ($centroids as $centroid) {
            $clusterPenduduk = HasilKmeans::where('centroid_id', $centroid->id)
                                        ->where('iterasi', Iterasi::max('iterasi'))
                                        ->with('penduduk')
                                        ->get();
            
            if ($clusterPenduduk->isEmpty()) {
                continue;
            }
            
            $oldValues = [
                'usia' => $centroid->usia,
                'tanggungan_num' => $centroid->tanggungan_num,
                'kondisi_rumah' => $centroid->kondisi_rumah,
                'status_kepemilikan' => $centroid->status_kepemilikan,
                'penghasilan_num' => $centroid->penghasilan_num
            ];
            
            // Hitung rata-rata untuk setiap kriteria
            $newValues = [
                'usia' => $clusterPenduduk->avg('penduduk.usia'),
                'tanggungan_num' => $clusterPenduduk->avg('penduduk.tanggungan'),
                'kondisi_rumah' => $this->calculateAverageKondisiRumah($clusterPenduduk),
                'status_kepemilikan' => $this->calculateAverageStatusKepemilikan($clusterPenduduk),
                'penghasilan_num' => $clusterPenduduk->avg('penduduk.penghasilan')
            ];
            
            // Update centroid
            $centroid->update($newValues);
            
            // Cek konvergensi
            foreach ($oldValues as $key => $oldValue) {
                if (abs($oldValue - $newValues[$key]) > 0.0001) {
                    $konvergen = false;
                    break;
                }
            }
        }
        
        return $konvergen;
    }

    private function convertKondisiRumah($kondisi)
    {
        return match($kondisi) {
            'baik' => 3,
            'cukup' => 2,
            'kurang' => 1,
            default => 0
        };
    }

    private function convertStatusKepemilikan($status)
    {
        return match($status) {
            'hak milik' => 3,
            'sewa' => 2,
            'numpang' => 1,
            default => 0
        };
    }

    private function calculateAverageKondisiRumah($penduduk)
    {
        $total = 0;
        $count = 0;
        
        foreach ($penduduk as $p) {
            $total += $this->convertKondisiRumah($p->penduduk->kondisi_rumah);
            $count++;
        }
        
        $average = $count > 0 ? $total / $count : 0;
        
        return match(true) {
            $average >= 2.5 => 'baik',
            $average >= 1.5 => 'cukup',
            default => 'kurang'
        };
    }

    private function calculateAverageStatusKepemilikan($penduduk)
    {
        $total = 0;
        $count = 0;
        
        foreach ($penduduk as $p) {
            $total += $this->convertStatusKepemilikan($p->penduduk->status_kepemilikan);
            $count++;
        }
        
        $average = $count > 0 ? $total / $count : 0;
        
        return match(true) {
            $average >= 2.5 => 'hak milik',
            $average >= 1.5 => 'sewa',
            default => 'numpang'
        };
    }
}