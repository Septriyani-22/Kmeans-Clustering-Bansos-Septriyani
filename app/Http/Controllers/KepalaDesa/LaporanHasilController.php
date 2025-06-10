<?php

namespace App\Http\Controllers\KepalaDesa;

use App\Http\Controllers\Controller;
use App\Models\HasilKmeans;

class LaporanHasilController extends Controller
{
    public function index()
    {
        $summary = HasilKmeans::select('cluster')
            ->selectRaw('count(*) as total')
            ->groupBy('cluster')
            ->pluck('total', 'cluster');
        return view('kepala_desa.laporan_hasil', compact('summary'));
    }

    public function export()
    {
        // Export summary ke Excel
    }
}