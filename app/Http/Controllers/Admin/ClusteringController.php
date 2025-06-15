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
        $penduduk = Penduduk::all();
        $centroids = Centroid::all();
        $iterasi = Iterasi::all();
        $hasilKmeans = HasilKmeans::with(['penduduk', 'centroid'])->get();
        
        return view('admin.clustering.index', compact('penduduk', 'centroids', 'iterasi', 'hasilKmeans'));
    }

    public function proses(Request $request)
    {
        try {
            $request->validate([
                'jumlah_cluster' => 'required|integer|min:2|max:10'
            ]);

            HasilKmeans::query()->delete();
            Centroid::query()->delete();

            $penduduks = Penduduk::all();
            if ($penduduks->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada data penduduk yang tersedia.');
            }

            $kriteria = Kriteria::all();
            if ($kriteria->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada kriteria yang tersedia.');
            }

            $centroidController = app(CentroidController::class);
            return $centroidController->prosesClustering($request, $penduduks, $kriteria);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function reset()
    {
        try {
            DB::beginTransaction();
            
            // Nonaktifkan foreign key checks sementara
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            
            // Hapus hanya data hasil perhitungan
            DB::table('hasil_kmeans')->truncate();
            DB::table('mapping_centroids')->truncate();
            
            // Aktifkan kembali foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            
            DB::commit();
            
            // Hapus data dari session
            session()->forget('distanceResults');
            
            return redirect()->route('admin.centroid.index')
                ->with('success', 'Data hasil perhitungan berhasil direset');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

}