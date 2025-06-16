<?php

namespace App\Http\Controllers;

use App\Models\Penduduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $nik = $request->query('nik');
        
        if (!$nik) {
            return redirect('/')->with('error', 'NIK tidak ditemukan');
        }

        // Cari data penduduk
        $penduduk = Penduduk::where('nik', $nik)->first();
        
        if (!$penduduk) {
            return redirect('/')->with('error', 'Data penduduk tidak ditemukan');
        }

        // Get distance results from session
        $distanceResults = session('distanceResults', []);
        
        // Find the result for this penduduk
        $result = null;
        foreach ($distanceResults as $r) {
            if ($r['penduduk']->id === $penduduk->id) {
                $result = $r;
                break;
            }
        }

        if (!$result) {
            return redirect('/')->with('error', 'Data clustering tidak ditemukan');
        }

        // Calculate cluster
        $minDistance = min($result['distances']);
        $clusterIndex = array_search($minDistance, $result['distances']);
        $cluster = 'C' . ($clusterIndex + 1);

        // Prepare data for view
        $data = [
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
                'Membutuhkan' : 
                ($cluster === 'C2' ? 'Tidak Membutuhkan' : 'Prioritas sedang')
        ];

        return view('welcome', compact('data'));
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