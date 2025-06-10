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
        $penduduk = Penduduk::orderBy('cluster')->get();
        $centroids = Centroid::orderBy('id')->get();
        
        // Calculate cluster statistics
        $clusterStats = [];
        for ($i = 1; $i <= 3; $i++) {
            $clusterData = $penduduk->where('cluster', $i);
            $clusterStats[$i] = [
                'count' => $clusterData->count(),
                'avg_usia' => $clusterData->avg('usia'),
                'avg_tanggungan' => $clusterData->avg('tanggungan'),
                'avg_penghasilan' => $clusterData->avg('penghasilan'),
                'baik' => $clusterData->where('kondisi_rumah', 'baik')->count(),
                'cukup' => $clusterData->where('kondisi_rumah', 'cukup')->count(),
                'kurang' => $clusterData->where('kondisi_rumah', 'kurang')->count(),
                'hak_milik' => $clusterData->where('status_kepemilikan', 'hak milik')->count(),
                'numpang' => $clusterData->where('status_kepemilikan', 'numpang')->count(),
                'sewa' => $clusterData->where('status_kepemilikan', 'sewa')->count(),
            ];
        }

        return view('admin.hasil-kmeans', compact('penduduk', 'centroids', 'clusterStats'));
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