<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penduduk;
use App\Models\HasilKmeans;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Get total counts
            $totalPenduduk = Penduduk::count();
            
            // Get clustering results
            $hasilKmeans = HasilKmeans::with(['penduduk', 'centroid'])
                ->orderBy('cluster')
                ->orderBy('jarak')
                ->get();

            // Count by cluster
            $clusterCounts = $hasilKmeans->groupBy('cluster')->map->count();
            
            $layakBantuan = $clusterCounts[1] ?? 0; // C1 - Membutuhkan
            $tidakLayak = $clusterCounts[2] ?? 0;   // C2 - Tidak Membutuhkan
            $prioritasSedang = $clusterCounts[3] ?? 0; // C3 - Prioritas Sedang

            // Calculate average income per cluster
            $avgIncomeC1 = $hasilKmeans->where('cluster', 1)->avg('penduduk.penghasilan') ?? 0;
            $avgIncomeC2 = $hasilKmeans->where('cluster', 2)->avg('penduduk.penghasilan') ?? 0;
            $avgIncomeC3 = $hasilKmeans->where('cluster', 3)->avg('penduduk.penghasilan') ?? 0;

            // Get cluster distribution data for charts
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

            return view('admin.dashboard', compact(
                'totalPenduduk',
                'hasilKmeans',
                'layakBantuan',
                'tidakLayak',
                'prioritasSedang',
                'avgIncomeC1',
                'avgIncomeC2',
                'avgIncomeC3',
                'clusterLabels',
                'clusterValues',
                'incomeLabels',
                'incomeValues'
            ));
        } catch (\Exception $e) {
            // If any error occurs, return default values
            return view('admin.dashboard', [
                'totalPenduduk' => 0,
                'hasilKmeans' => collect(),
                'layakBantuan' => 0,
                'tidakLayak' => 0,
                'prioritasSedang' => 0,
                'avgIncomeC1' => 0,
                'avgIncomeC2' => 0,
                'avgIncomeC3' => 0,
                'clusterLabels' => ['Belum ada data'],
                'clusterValues' => [0],
                'incomeLabels' => array_keys($incomeRanges ?? []),
                'incomeValues' => array_fill(0, 3, 0)
            ]);
        }
    }
} 