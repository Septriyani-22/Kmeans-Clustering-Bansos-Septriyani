<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HasilKmeans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Penduduk;
use App\Models\Centroid;

class HasilKmeansController extends Controller
{
    public function index()
    {
        $hasilKmeans = HasilKmeans::with(['penduduk', 'centroid'])
            ->orderBy('cluster')
            ->orderBy('jarak')
            ->get();

        // Count total data
        $totalData = $hasilKmeans->count();

        // Count by cluster
        $clusterCounts = $hasilKmeans->groupBy('cluster')->map->count();
        
        $layakBantuan = $clusterCounts[1] ?? 0; // C1 - Membutuhkan
        $tidakLayak = $clusterCounts[2] ?? 0;   // C2 - Tidak Membutuhkan
        $prioritasSedang = $clusterCounts[3] ?? 0; // C3 - Prioritas Sedang

        // Calculate average score
        $avgScore = $hasilKmeans->avg('jarak');

        return view('admin.hasil-kmeans.index', compact(
            'hasilKmeans',
            'totalData',
            'layakBantuan',
            'tidakLayak',
            'prioritasSedang',
            'avgScore'
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
} 