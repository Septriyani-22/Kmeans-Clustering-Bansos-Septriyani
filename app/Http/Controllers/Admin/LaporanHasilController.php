<?php

namespace App\Http\Controllers\Admin;

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
        return view('admin.laporan_hasil', compact('summary'));
    }

    public function export()
    {
        // Export summary ke Excel
    }
}