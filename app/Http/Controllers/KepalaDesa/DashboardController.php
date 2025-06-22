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
        $hasilKmeans = HasilKmeans::with('penduduk', 'centroid')->get();

        $clusterCounts = $hasilKmeans->groupBy('centroid.nama_centroid')
            ->map->count()
            ->collect()
            ->pipe(function ($collection) {
                return [
                    'C1' => $collection->get('C1', 0),
                    'C2' => $collection->get('C2', 0),
                    'C3' => $collection->get('C3', 0),
                ];
            });

        $results = $hasilKmeans->map(function ($hasil) {
            if (!$hasil->penduduk || !$hasil->centroid) {
                return null;
            }
            $cluster = $hasil->centroid->nama_centroid;
            return [
                'nik' => $hasil->penduduk->nik,
                'nama' => $hasil->penduduk->nama,
                'usia' => $hasil->penduduk->usia . ' tahun',
                'tanggungan' => $hasil->penduduk->tanggungan . ' orang',
                'kondisi_rumah' => $hasil->penduduk->kondisi_rumah,
                'status_kepemilikan' => $hasil->penduduk->status_kepemilikan,
                'penghasilan' => 'Rp ' . number_format($hasil->penduduk->penghasilan, 0, ',', '.'),
                'cluster' => $cluster,
                'kelayakan' => $cluster === 'C1' ? 'Layak' : 'Tidak Layak',
                'keterangan' => $cluster === 'C1' ? 'Membutuhkan' : ($cluster === 'C2' ? 'Tidak Membutuhkan' : 'Prioritas sedang')
            ];
        })->filter()->values();

        $totalData = $results->count();
        $paginatedResults = new LengthAwarePaginator(
            $results->forPage(request()->get('page', 1), 10),
            $totalData,
            10,
            request()->get('page', 1),
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
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