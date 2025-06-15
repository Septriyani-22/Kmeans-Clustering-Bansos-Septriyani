<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penduduk;
use App\Models\Centroid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index()
    {
        // Get total penduduk count
        $totalPenduduk = Penduduk::count();
        
        // Get distance results from session
        $distanceResults = Session::get('distanceResults', []);
        
        // Initialize cluster counts
        $clusterCounts = [
            'C1' => 0,
            'C2' => 0,
            'C3' => 0
        ];
        
        // Process distance results to get cluster counts
        if (!empty($distanceResults)) {
            foreach ($distanceResults as $result) {
                $minDistance = min($result['distances']);
                $clusterIndex = array_search($minDistance, $result['distances']);
                $clusterCounts['C' . ($clusterIndex + 1)]++;
            }
        }
        
        // Calculate total data
        $totalData = array_sum($clusterCounts);
        
        // Get latest centroids with proper formatting
        $centroids = Centroid::latest()->take(3)->get()->map(function ($centroid) {
            return [
                'cluster' => $centroid->cluster,
                'usia' => $centroid->usia . ' tahun',
                'jumlah_tanggungan' => $centroid->jumlah_tanggungan . ' orang',
                'kondisi_rumah' => $this->formatKondisiRumah($centroid->kondisi_rumah),
                'status_kepemilikan' => $this->formatStatusKepemilikan($centroid->status_kepemilikan),
                'jumlah_penghasilan' => 'Rp ' . number_format($centroid->jumlah_penghasilan, 0, ',', '.')
            ];
        });
        
        // Get recent penduduk with proper formatting
        $recentPenduduk = Penduduk::latest()->take(5)->get()->map(function ($penduduk) {
            return [
                'nik' => $penduduk->nik,
                'nama' => $penduduk->nama,
                'usia' => $penduduk->usia . ' tahun',
                'tanggungan' => $penduduk->tanggungan . ' orang'
            ];
        });
        
        // Prepare data for charts
        $chartData = [
            'labels' => ['C1 (Membutuhkan)', 'C2 (Tidak Membutuhkan)', 'C3 (Prioritas Sedang)'],
            'data' => array_values($clusterCounts)
        ];
        
        return view('admin.dashboard', compact(
            'totalPenduduk',
            'clusterCounts',
            'totalData',
            'centroids',
            'chartData',
            'recentPenduduk'
        ));
    }

    private function formatKondisiRumah($kondisi)
    {
        $kondisiMap = [
            1 => 'Layak',
            2 => 'Tidak Layak',
            3 => 'Sangat Tidak Layak'
        ];
        return $kondisiMap[$kondisi] ?? 'Tidak Diketahui';
    }

    private function formatStatusKepemilikan($status)
    {
        $statusMap = [
            1 => 'Milik Sendiri',
            2 => 'Kontrak',
            3 => 'Sewa'
        ];
        return $statusMap[$status] ?? 'Tidak Diketahui';
    }
} 