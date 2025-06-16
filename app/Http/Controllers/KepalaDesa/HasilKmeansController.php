<?php

namespace App\Http\Controllers\KepalaDesa;

use App\Http\Controllers\Controller;
use App\Models\Penduduk;
use Illuminate\Http\Request;

class HasilKmeansController extends Controller
{
    public function index()
    {
        // Get distance results from session
        $distanceResults = session('distanceResults', []);
        
        // Calculate totals
        $totalData = count($distanceResults);
        
        // Initialize cluster counts
        $clusterCounts = [
            'C1' => 0,
            'C2' => 0,
            'C3' => 0
        ];

        // Process distance results to get cluster counts
        $hasilKmeans = collect($distanceResults)->map(function($result) use (&$clusterCounts) {
            $minDistance = min($result['distances']);
            $clusterIndex = array_search($minDistance, $result['distances']);
            $cluster = 'C' . ($clusterIndex + 1);
            $clusterCounts[$cluster]++;
            
            // Get penduduk data
            $penduduk = Penduduk::find($result['penduduk']->id);
            
            return (object)[
                'nama_penduduk' => $penduduk->nama,
                'usia' => $penduduk->usia,
                'jumlah_tanggungan' => $penduduk->tanggungan,
                'kondisi_rumah' => $penduduk->kondisi_rumah,
                'status_kepemilikan' => $penduduk->status_kepemilikan,
                'jumlah_penghasilan' => $penduduk->penghasilan,
                'cluster' => $cluster,
                'kelayakan' => $cluster === 'C1' ? 'Layak' : 'Tidak Layak',
                'keterangan' => $cluster === 'C1' ? 
                    'Membutuhkan' : 
                    ($cluster === 'C2' ? 'Tidak Membutuhkan' : 'Prioritas sedang')
            ];
        });

        // Get counts for each category
        $layakBantuan = $clusterCounts['C1'];     // C1 - Membutuhkan
        $tidakLayak = $clusterCounts['C2'];       // C2 - Tidak Membutuhkan
        $prioritasSedang = $clusterCounts['C3'];  // C3 - Prioritas Sedang

        return view('kepala_desa.hasil-kmeans.index', compact(
            'hasilKmeans',
            'totalData',
            'layakBantuan',
            'tidakLayak',
            'prioritasSedang'
        ));
    }

    public function print()
    {
        // Get distance results from session
        $distanceResults = session('distanceResults', []);
        
        // Calculate totals
        $totalData = count($distanceResults);
        
        // Initialize cluster counts
        $clusterCounts = [
            'C1' => 0,
            'C2' => 0,
            'C3' => 0
        ];

        // Process distance results to get cluster counts
        $hasilKmeans = collect($distanceResults)->map(function($result) use (&$clusterCounts) {
            $minDistance = min($result['distances']);
            $clusterIndex = array_search($minDistance, $result['distances']);
            $cluster = 'C' . ($clusterIndex + 1);
            $clusterCounts[$cluster]++;
            
            // Get penduduk data
            $penduduk = Penduduk::find($result['penduduk']->id);
            
            return (object)[
                'nama_penduduk' => $penduduk->nama,
                'usia' => $penduduk->usia,
                'jumlah_tanggungan' => $penduduk->tanggungan,
                'kondisi_rumah' => $penduduk->kondisi_rumah,
                'status_kepemilikan' => $penduduk->status_kepemilikan,
                'jumlah_penghasilan' => $penduduk->penghasilan,
                'cluster' => $cluster,
                'kelayakan' => $cluster === 'C1' ? 'Layak' : 'Tidak Layak',
                'keterangan' => $cluster === 'C1' ? 
                    'Membutuhkan' : 
                    ($cluster === 'C2' ? 'Tidak Membutuhkan' : 'Prioritas sedang')
            ];
        });

        // Get counts for each category
        $layakBantuan = $clusterCounts['C1'];     // C1 - Membutuhkan
        $tidakLayak = $clusterCounts['C2'];       // C2 - Tidak Membutuhkan
        $prioritasSedang = $clusterCounts['C3'];  // C3 - Prioritas Sedang

        return view('kepala_desa.hasil-kmeans.print', compact(
            'hasilKmeans',
            'totalData',
            'layakBantuan',
            'tidakLayak',
            'prioritasSedang'
        ));
    }
} 