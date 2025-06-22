<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penduduk;
use App\Models\Centroid;
use App\Models\Iterasi;
use App\Models\HasilKmeans;
use App\Models\Kriteria;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\MappingCentroid;

class ClusteringController extends Controller
{
    public function index()
    {
        return view('admin.clustering.index');
    }

    public function proses(Request $request)
    {
        try {
            $request->validate([
                'jumlah_cluster' => 'required|integer|min:2|max:10'
            ]);
            $centroidController = app(CentroidController::class);
            return $centroidController->calculateDistances();

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function reset()
    {
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            
            DB::table('hasil_kmeans')->truncate();
            DB::table('mapping_centroids')->truncate();
            DB::table('centroids')->truncate();
            DB::table('iterasi')->truncate();
            
            // Aktifkan kembali foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            
            // Hapus data dari session
            session()->forget(['distanceResults', 'centroids', 'iterasi', 'hasilKmeans']);
            
            return redirect()->route('admin.clustering.index')
                ->with('success', 'Semua data perhitungan berhasil direset');
        } catch (\Exception $e) {
            Log::error('Error resetting calculations: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mereset data: ' . $e->getMessage());
        }
    }
}