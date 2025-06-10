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
            ->orderBy('penduduk_id')
            ->get()
            ->map(function ($hasil) {
                $clusterName = match($hasil->cluster) {
                    1 => 'Membutuhkan',
                    2 => 'Tidak Membutuhkan',
                    3 => 'Prioritas Sedang',
                    default => 'Tidak Diketahui'
                };
                
                return [
                    'no' => $hasil->penduduk->id,
                    'nama' => $hasil->penduduk->nama,
                    'kelas' => $clusterName
                ];
            });

        $totalData = $hasilKmeans->count();
        $layakBantuan = $hasilKmeans->where('kelas', 'Membutuhkan')->count();
        $tidakLayak = $hasilKmeans->where('kelas', 'Tidak Membutuhkan')->count();
        $prioritasSedang = $hasilKmeans->where('kelas', 'Prioritas Sedang')->count();
        
        // Calculate average score (using cluster numbers as scores)
        $avgScore = $hasilKmeans->avg(function($hasil) {
            return match($hasil['kelas']) {
                'Membutuhkan' => 1,
                'Tidak Membutuhkan' => 2,
                'Prioritas Sedang' => 3,
                default => 0
            };
        });

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