<?php

namespace App\Http\Controllers\Penduduk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penduduk;
use App\Models\HasilKmeans;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Get penduduk data for the logged-in user
            $penduduk = Penduduk::where('user_id', auth()->id())->first();
            
            if (!$penduduk) {
                return redirect()->route('login')
                    ->with('error', 'Data penduduk tidak ditemukan.');
            }

            // Get clustering result for this penduduk
            $hasilKmeans = HasilKmeans::with(['penduduk', 'centroid'])
                ->where('penduduk_id', $penduduk->id)
                ->first();

            return view('penduduk.dashboard', compact('penduduk', 'hasilKmeans'));
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
} 