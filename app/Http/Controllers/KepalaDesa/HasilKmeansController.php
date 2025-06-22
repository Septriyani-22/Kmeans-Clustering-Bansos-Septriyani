<?php

namespace App\Http\Controllers\KepalaDesa;

use App\Http\Controllers\Controller;
use App\Models\HasilKmeans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HasilKmeansController extends Controller
{
    public function index()
    {
        $results = HasilKmeans::with('penduduk')->paginate(10);

        // Count clusters directly from the results table
        $clusterCounts = HasilKmeans::select('centroid_id', DB::raw('count(*) as count'))
            ->groupBy('centroid_id')
            ->pluck('count', 'centroid_id')
            ->pipe(function ($collection) {
                return [
                    'C1' => $collection->get(1, 0),
                    'C2' => $collection->get(2, 0),
                    'C3' => $collection->get(3, 0),
                ];
            });

        $totalData = $results->total();

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
        $result = HasilKmeans::with('penduduk')->findOrFail($id);
        return view('kepala_desa.hasil_kmeans.show', compact('result'));
    }
} 