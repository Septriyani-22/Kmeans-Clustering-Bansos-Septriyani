<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HasilKmeans;
use App\Models\Kriteria;
use App\Models\Penduduk;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $totalPenduduk = Penduduk::count();
        $totalKriteria = Kriteria::where('is_aktif', true)->count();
        
        $layakBantuan = HasilKmeans::where('kelayakan', 'Layak')->count();
        $tidakLayak = HasilKmeans::where('kelayakan', 'Tidak Layak')->count();
        
        $cluster1 = HasilKmeans::where('cluster', 'Cluster 1')->count();
        $cluster2 = HasilKmeans::where('cluster', 'Cluster 2')->count();
        $cluster3 = HasilKmeans::where('cluster', 'Cluster 3')->count();

        // Get latest results for dashboard
        $latestResults = HasilKmeans::with(['penduduk', 'centroid'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $clusterStats = \App\Models\HasilKmeans::select('cluster', \DB::raw('count(*) as count'))
            ->groupBy('cluster')
            ->get();

        return view('admin.dashboard', compact(
            'totalPenduduk',
            'totalKriteria',
            'layakBantuan',
            'tidakLayak',
            'cluster1',
            'cluster2',
            'cluster3',
            'latestResults',
            'clusterStats'
        ));
    }
} 