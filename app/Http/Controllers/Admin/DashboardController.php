<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penduduk;
use App\Models\Centroid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        // Get total penduduk count
        $totalPenduduk = Penduduk::count();
        
        // Get distance results from session
        $distanceResults = session('distanceResults', []);
        
        // Initialize cluster counts
        $clusterCounts = [
            'C1' => 0,
            'C2' => 0,
            'C3' => 0
        ];
        
        // Process distance results to get cluster counts and prepare data for pagination
        $results = [];
        if (!empty($distanceResults)) {
            foreach ($distanceResults as $result) {
                $minDistance = min($result['distances']);
                $clusterIndex = array_search($minDistance, $result['distances']);
                $cluster = 'C' . ($clusterIndex + 1);
                $clusterCounts[$cluster]++;
                
                // Get penduduk data
                $penduduk = Penduduk::find($result['penduduk']->id);
                if ($penduduk) {
                    $results[] = [
                        'nik' => $penduduk->nik,
                        'nama' => $penduduk->nama,
                        'usia' => $penduduk->usia . ' tahun',
                        'tanggungan' => $penduduk->tanggungan . ' orang',
                        'kondisi_rumah' => $this->formatKondisiRumah($penduduk->kondisi_rumah),
                        'status_kepemilikan' => $this->formatStatusKepemilikan($penduduk->status_kepemilikan),
                        'penghasilan' => 'Rp ' . number_format($penduduk->penghasilan, 0, ',', '.'),
                        'cluster' => $cluster,
                        'kelayakan' => $cluster === 'C1' ? 'Layak' : 'Tidak Layak',
                        'keterangan' => $cluster === 'C1' ? 
                            'Sangat membutuhkan bantuan' : 
                            ($cluster === 'C2' ? 'Tidak membutuhkan bantuan' : 'Prioritas sedang')
                    ];
                }
            }
        }
        
        // Calculate total data
        $totalData = array_sum($clusterCounts);
        
        // Paginate results
        $page = request()->get('page', 1);
        $perPage = 10;
        $offset = ($page - 1) * $perPage;
        
        $paginatedResults = new LengthAwarePaginator(
            array_slice($results, $offset, $perPage),
            count($results),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
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
            'paginatedResults',
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