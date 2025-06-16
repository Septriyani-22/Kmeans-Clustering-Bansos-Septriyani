<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HasilKmeans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Penduduk;
use App\Models\Centroid;
use PDF;
use App\Models\MappingCentroid;

class HasilKmeansController extends Controller
{
    public function index()
    {
        // Get distance results from session
        $distanceResults = session('distanceResults', []);
        
        // Calculate totals
        $totalData = count($distanceResults);
        
        // Initialize cluster counts
        $clusterCounts = [
            'C1' => 0,
            'C2' => 0,
            'C3' => 0
        ];

        // Process distance results to get cluster counts
        $hasilKmeans = collect($distanceResults)->map(function($result) use (&$clusterCounts) {
            $minDistance = min($result['distances']);
            $clusterIndex = array_search($minDistance, $result['distances']);
            $cluster = 'C' . ($clusterIndex + 1);
            $clusterCounts[$cluster]++;
            
            // Get penduduk data
            $penduduk = Penduduk::find($result['penduduk']->id);
            
            return (object)[
                'nama_penduduk' => $penduduk->nama,
                'usia' => $penduduk->usia,
                'jumlah_tanggungan' => $penduduk->tanggungan,
                'kondisi_rumah' => $penduduk->kondisi_rumah,
                'status_kepemilikan' => $penduduk->status_kepemilikan,
                'jumlah_penghasilan' => $penduduk->penghasilan,
                'cluster' => $cluster,
                // 'kelayakan' => $cluster === 'C1' ? 'Layak' : 'Tidak Layak',
                'keterangan' => $cluster === 'C1' ? 
                    'Membutuhkan' : 
                    ($cluster === 'C2' ? 'Tidak Membutuhkan' : 'Prioritas sedang')
            ];
        });

        // Get counts for each category
        $layakBantuan = $clusterCounts['C1'];     // C1 - Membutuhkan
        $tidakLayak = $clusterCounts['C2'];       // C2 - Tidak Membutuhkan
        $prioritasSedang = $clusterCounts['C3'];  // C3 - Prioritas Sedang

        return view('admin.hasil-kmeans.index', compact(
            'hasilKmeans',
            'totalData',
            'layakBantuan',
            'tidakLayak',
            'prioritasSedang'
        ));
    }

    public function export()
    {
        $hasilKmeans = HasilKmeans::with(['penduduk', 'centroid'])
            ->orderBy('skor_kelayakan', 'desc')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="hasil-kmeans.csv"',
        ];

        $callback = function() use ($hasilKmeans) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'NIK',
                'Nama',
                'Cluster',
                'Skor Kelayakan',
                'Kelayakan',
                'Skor Penghasilan',
                'Skor Tanggungan',
                'Skor Kondisi Rumah',
                'Skor Status Kepemilikan',
                'Skor Usia'
            ]);

            // Add data
            foreach ($hasilKmeans as $hasil) {
                fputcsv($file, [
                    $hasil->penduduk->nik,
                    $hasil->penduduk->nama,
                    $hasil->centroid->nama_centroid,
                    $hasil->skor_kelayakan,
                    $hasil->kelayakan,
                    $hasil->skor_penghasilan,
                    $hasil->skor_tanggungan,
                    $hasil->skor_kondisi_rumah,
                    $hasil->skor_status_kepemilikan,
                    $hasil->skor_usia
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function print(Request $request)
    {
        $clusters = $request->input('clusters', [1, 2, 3]);
        
        // Ambil hasil perhitungan jarak dari session
        $distanceResults = session('distanceResults', []);
        
        // Hitung total data
        $totalData = count($distanceResults);

        // Hitung jumlah per cluster berdasarkan jarak terdekat
        $clusterCounts = [
            'C1' => 0,
            'C2' => 0,
            'C3' => 0
        ];

        foreach ($distanceResults as $result) {
            $minDistance = min($result['distances']);
            $clusterIndex = array_search($minDistance, $result['distances']);
            $cluster = 'C' . ($clusterIndex + 1);
            $clusterCounts[$cluster]++;
        }

        $layakBantuan = $clusterCounts['C1']; // C1 - Membutuhkan
        $tidakLayak = $clusterCounts['C2'];   // C2 - Tidak Membutuhkan
        $prioritasSedang = $clusterCounts['C3']; // C3 - Prioritas Sedang

        // Ambil data penduduk untuk setiap hasil
        $hasilKmeans = collect($distanceResults)->map(function($result) {
            $minDistance = min($result['distances']);
            $clusterIndex = array_search($minDistance, $result['distances']);
            $cluster = 'C' . ($clusterIndex + 1);
            
            // Ambil data penduduk
            $penduduk = Penduduk::find($result['penduduk']->id);
            
            return (object)[
                'nama_penduduk' => $penduduk->nama,
                'usia' => $penduduk->usia,
                'jumlah_tanggungan' => $penduduk->tanggungan,
                'kondisi_rumah' => $penduduk->kondisi_rumah,
                'status_kepemilikan' => $penduduk->status_kepemilikan,
                'jumlah_penghasilan' => $penduduk->penghasilan,
                'cluster' => $cluster,
                'kelayakan' => $cluster === 'C1' ? 'Layak' : 'Tidak Layak',
                'keterangan' => $cluster === 'C1' ? 
                    'Membutuhkan' : 
                    ($cluster === 'C2' ? 'Tidak Membutuhkan' : 'Prioritas sedang')
            ];
        })->filter(function($hasil) use ($clusters) {
            // Filter berdasarkan cluster yang dipilih
            $clusterNumber = (int)substr($hasil->cluster, 1);
            return in_array($clusterNumber, $clusters);
        });

        $data = [
            'hasilKmeans' => $hasilKmeans,
            'totalData' => $hasilKmeans->count(),
            'layakBantuan' => $layakBantuan,
            'tidakLayak' => $tidakLayak,
            'prioritasSedang' => $prioritasSedang,
            'selectedClusters' => $clusters
        ];

        $pdf = PDF::loadView('admin.hasil-kmeans.print', $data);
        return $pdf->stream('hasil-kmeans.pdf');
    }
} 