<?php

namespace App\Http\Controllers\KepalaDesa;

use App\Http\Controllers\Controller;
use App\Models\HasilKmeans;
use Illuminate\Http\Request;

class HasilKmeansController extends Controller
{
    public function index()
    {
        $results = HasilKmeans::with('penduduk', 'centroid')->paginate(10);

        $clusterCounts = HasilKmeans::join('centroids', 'hasil_kmeans.centroid_id', '=', 'centroids.id')
            ->selectRaw('centroids.nama_centroid, count(*) as count')
            ->groupBy('centroids.nama_centroid')
            ->get()
            ->pluck('count', 'nama_centroid')
            ->pipe(function ($collection) {
                return [
                    'C1' => $collection->get('C1', 0),
                    'C2' => $collection->get('C2', 0),
                    'C3' => $collection->get('C3', 0),
                ];
            });

        $totalData = array_sum($clusterCounts);

        $chartData = [
            'labels' => ['C1 (Membutuhkan)', 'C2 (Tidak Membutuhkan)', 'C3 (Prioritas Sedang)'],
            'data' => array_values($clusterCounts)
        ];

        return view('kepala_desa.hasil_kmeans.index', compact(
            'results', 'clusterCounts', 'totalData', 'chartData'
        ));
    }

    public function show($id)
    {
        $result = HasilKmeans::with('penduduk', 'centroid')->findOrFail($id);
        return view('kepala_desa.hasil_kmeans.show', compact('result'));
    }
} 