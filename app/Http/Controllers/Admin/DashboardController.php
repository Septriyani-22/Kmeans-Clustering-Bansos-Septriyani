<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penduduk;
use App\Models\Kriteria;
use App\Models\Centroid;
use App\Models\HasilKmeans;
use Illuminate\Support\Facades\DB;
use App\Models\Activity;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Get basic statistics
            $totalPenduduk = Penduduk::count();
            $totalKriteria = Kriteria::where('is_aktif', true)->count();
            $totalCentroid = Centroid::count();
            $totalHasilClustering = HasilKmeans::count();

            // Get layak/tidak layak statistics
            $layakBantuan = HasilKmeans::where('kelayakan', 'Layak')->count();
            $tidakLayak = HasilKmeans::where('kelayakan', 'Tidak Layak')->count();

            // Get latest clustering results with relationships
            $latestResults = HasilKmeans::with(['penduduk', 'centroid'])
                ->latest()
                ->take(5)
                ->get();

            // Get cluster distribution data
            $clusterData = DB::table('hasil_kmeans')
                ->select('cluster', DB::raw('count(*) as total'))
                ->groupBy('cluster')
                ->orderBy('cluster')
                ->get();

            $clusterLabels = [];
            $clusterValues = [];
            foreach ($clusterData as $data) {
                $clusterLabels[] = 'Cluster ' . $data->cluster;
                $clusterValues[] = $data->total;
            }

            // Get income distribution data
            $incomeData = DB::table('penduduks')
                ->select('penghasilan', DB::raw('count(*) as total'))
                ->groupBy('penghasilan')
                ->get();

            $incomeLabels = [];
            $incomeValues = [];
            $incomeRanges = [
                'Kurang Dari 500' => 'Kurang Dari 500',
                '500-1 juta' => '500-1 juta',
                'Lebih Dari 1 juta' => 'Lebih Dari 1 juta'
            ];

            // Initialize with zeros
            foreach ($incomeRanges as $range => $label) {
                $incomeLabels[] = $label;
                $incomeValues[] = 0;
            }

            // Fill with actual data
            foreach ($incomeData as $data) {
                $index = array_search($data->penghasilan, $incomeLabels);
                if ($index !== false) {
                    $incomeValues[$index] = $data->total;
                }
            }

            // Get cluster statistics with details
            $clusterStats = DB::table('hasil_kmeans')
                ->select('cluster', 'kelayakan', DB::raw('count(*) as total'))
                ->groupBy('cluster', 'kelayakan')
                ->get()
                ->groupBy('cluster')
                ->map(function ($group) {
                    return [
                        'total' => $group->sum('total'),
                        'layak' => $group->where('kelayakan', 'Layak')->sum('total'),
                        'tidak_layak' => $group->where('kelayakan', 'Tidak Layak')->sum('total')
                    ];
                });

            // Get average scores
            $avgScores = HasilKmeans::select(
                DB::raw('AVG(skor_penghasilan) as avg_penghasilan'),
                DB::raw('AVG(skor_tanggungan) as avg_tanggungan'),
                DB::raw('AVG(skor_kondisi_rumah) as avg_kondisi_rumah'),
                DB::raw('AVG(skor_status_kepemilikan) as avg_status_kepemilikan'),
                DB::raw('AVG(skor_usia) as avg_usia'),
                DB::raw('AVG(skor_kelayakan) as avg_kelayakan')
            )->first();

            // Get top 5 highest scores
            $topScores = HasilKmeans::with('penduduk')
                ->orderBy('skor_kelayakan', 'desc')
                ->take(5)
                ->get();

            $avgScore = HasilKmeans::avg('skor_kelayakan');

            $totalHasilKmeans = HasilKmeans::count();
            $recentActivities = Activity::latest()->take(5)->get();

            return view('admin.dashboard', compact(
                'totalPenduduk',
                'totalKriteria',
                'totalCentroid',
                'totalHasilClustering',
                'layakBantuan',
                'tidakLayak',
                'latestResults',
                'clusterLabels',
                'clusterValues',
                'incomeLabels',
                'incomeValues',
                'clusterStats',
                'avgScores',
                'topScores',
                'avgScore',
                'totalHasilKmeans',
                'recentActivities'
            ));
        } catch (\Exception $e) {
            // If any error occurs, return default values
            return view('admin.dashboard', [
                'totalPenduduk' => 0,
                'totalKriteria' => 0,
                'totalCentroid' => 0,
                'totalHasilClustering' => 0,
                'layakBantuan' => 0,
                'tidakLayak' => 0,
                'latestResults' => collect(),
                'clusterLabels' => ['Belum ada data'],
                'clusterValues' => [0],
                'incomeLabels' => array_keys($incomeRanges ?? []),
                'incomeValues' => array_fill(0, 3, 0),
                'clusterStats' => collect(),
                'avgScores' => (object)[
                    'avg_penghasilan' => 0,
                    'avg_tanggungan' => 0,
                    'avg_kondisi_rumah' => 0,
                    'avg_status_kepemilikan' => 0,
                    'avg_usia' => 0,
                    'avg_kelayakan' => 0
                ],
                'topScores' => collect(),
                'avgScore' => 0,
                'totalHasilKmeans' => 0,
                'recentActivities' => collect()
            ]);
        }
    }
} 