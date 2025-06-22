<?php

namespace App\Http\Controllers\KepalaDesa;

use App\Http\Controllers\Controller;
use App\Models\Penduduk;
use App\Models\HasilKmeans;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPenduduk = Penduduk::count();

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

        $paginatedResults = HasilKmeans::with('penduduk')->paginate(10);
        
        $totalData = $paginatedResults->total();
        
        $recentPenduduk = Penduduk::latest()->take(5)->get()->map(function ($penduduk) {
            return [
                'nik' => $penduduk->nik,
                'nama' => $penduduk->nama,
                'usia' => $penduduk->usia . ' tahun',
                'tanggungan' => $penduduk->tanggungan . ' orang'
            ];
        });
        
        $chartData = [
            'labels' => ['C1 (Membutuhkan)', 'C2 (Tidak Membutuhkan)', 'C3 (Prioritas Sedang)'],
            'data' => array_values($clusterCounts)
        ];
        
        return view('kepala_desa.dashboard', compact(
            'totalPenduduk', 'clusterCounts', 'totalData', 'paginatedResults', 'chartData', 'recentPenduduk'
        ));
    }
} 