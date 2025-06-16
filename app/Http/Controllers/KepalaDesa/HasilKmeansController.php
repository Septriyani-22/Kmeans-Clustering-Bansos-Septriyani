<?php

namespace App\Http\Controllers\KepalaDesa;

use App\Http\Controllers\Controller;
use App\Models\HasilKmeans;
use App\Models\MappingCentroid;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HasilKmeansController extends Controller
{
    public function index()
    {
        // Ambil data hasil k-means dari mapping_centroids
        $results = MappingCentroid::with('penduduk')
            ->orderBy('cluster')
            ->paginate(10);

        // Hitung jumlah per cluster
        $clusterCounts = [
            'C1' => MappingCentroid::where('cluster', 'C1')->count(),
            'C2' => MappingCentroid::where('cluster', 'C2')->count(),
            'C3' => MappingCentroid::where('cluster', 'C3')->count()
        ];

        // Hitung total data
        $totalData = array_sum($clusterCounts);

        // Persiapkan data untuk grafik
        $chartData = [
            'labels' => ['C1 (Membutuhkan)', 'C2 (Tidak Membutuhkan)', 'C3 (Prioritas Sedang)'],
            'data' => array_values($clusterCounts)
        ];

        return view('kepala_desa.hasil_kmeans.index', compact(
            'results',
            'clusterCounts',
            'totalData',
            'chartData'
        ));
    }

    public function show($id)
    {
        $result = MappingCentroid::with('penduduk')->findOrFail($id);
        return view('kepala_desa.hasil_kmeans.show', compact('result'));
    }
} 