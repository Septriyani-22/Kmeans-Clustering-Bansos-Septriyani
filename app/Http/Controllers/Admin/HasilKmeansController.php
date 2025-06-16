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
        // Get data from database
        $hasilKmeans = HasilKmeans::with(['penduduk', 'centroid'])->get();
        
        // Calculate totals
        $totalData = $hasilKmeans->count();
        
        // Count by cluster
        $layakBantuan = $hasilKmeans->where('centroid.nama_centroid', 'C1')->count();
        $tidakLayak = $hasilKmeans->where('centroid.nama_centroid', 'C2')->count();
        $prioritasSedang = $hasilKmeans->where('centroid.nama_centroid', 'C3')->count();

        // Format data for view
        $hasilKmeans = $hasilKmeans->map(function($hasil) {
            return (object)[
                'nama_penduduk' => $hasil->penduduk->nama,
                'usia' => $hasil->penduduk->usia,
                'jumlah_tanggungan' => $hasil->penduduk->tanggungan,
                'kondisi_rumah' => $hasil->penduduk->kondisi_rumah,
                'status_kepemilikan' => $hasil->penduduk->status_kepemilikan,
                'jumlah_penghasilan' => $hasil->penduduk->penghasilan,
                'cluster' => $hasil->centroid->nama_centroid,
                'kelayakan' => $hasil->centroid->nama_centroid === 'C1' ? 'Layak' : 'Tidak Layak',
                'keterangan' => $hasil->centroid->nama_centroid === 'C1' ? 
                    'Membutuhkan' : 
                    ($hasil->centroid->nama_centroid === 'C2' ? 'Tidak Membutuhkan' : 'Prioritas sedang')
            ];
        });

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