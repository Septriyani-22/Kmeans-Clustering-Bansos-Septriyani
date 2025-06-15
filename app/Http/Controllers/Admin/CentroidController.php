<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Centroid;
use App\Models\Penduduk;
use App\Models\Kriteria;
use App\Http\Controllers\Admin\ClusteringController;
use App\Models\MappingCentroid;

class CentroidController extends Controller
{
    protected $clusteringController;

    public function __construct(ClusteringController $clusteringController)
    {
        $this->clusteringController = $clusteringController;
    }

    public function index()
    {
        $centroids = Centroid::all();
        $penduduks = Penduduk::all();
        $kriteria = Kriteria::all();
        $distanceResults = [];
        $mappings = MappingCentroid::with(['penduduk', 'centroid'])->get();

        // Validate required data
        if ($penduduks->isEmpty()) {
            return view('admin.centroid.index', compact('centroids', 'distanceResults', 'mappings'))
                ->with('warning', 'Data penduduk masih kosong. Silakan tambahkan data penduduk terlebih dahulu.');
        }

        if ($kriteria->isEmpty()) {
            return view('admin.centroid.index', compact('centroids', 'distanceResults', 'mappings'))
                ->with('warning', 'Data kriteria masih kosong. Silakan tambahkan data kriteria terlebih dahulu.');
        }

        // Only calculate distances if there are centroids
        if ($centroids->isNotEmpty()) {
            try {
            foreach ($penduduks as $penduduk) {
                $distances = [];
                foreach ($centroids as $centroid) {
                    $usia = $penduduk->usia;
                    $usiaValue = $this->clusteringController->getUsiaValue($usia, $kriteria);
                    $tanggunganValue = $this->clusteringController->getTanggunganValue($penduduk->tanggungan, $kriteria);
                    $kondisiRumahValue = $this->clusteringController->getKondisiRumahValue($penduduk->kondisi_rumah, $kriteria);
                    $statusKepemilikanValue = $this->clusteringController->getStatusKepemilikanValue($penduduk->status_kepemilikan, $kriteria);
                    $penghasilanValue = $this->clusteringController->getPenghasilanValue($penduduk->penghasilan, $kriteria);
                        
                    $dist = sqrt(
                        pow($usiaValue - $centroid->usia, 2) +
                        pow($tanggunganValue - $centroid->tanggungan_num, 2) +
                        pow($kondisiRumahValue - $this->clusteringController->convertKondisiRumah($centroid->kondisi_rumah), 2) +
                        pow($statusKepemilikanValue - $this->clusteringController->convertStatusKepemilikan($centroid->status_kepemilikan), 2) +
                        pow($penghasilanValue - $centroid->penghasilan_num, 2)
                    );
                    $distances[] = $dist;
                }
                $min = min($distances);
                $nearestCluster = array_search($min, $distances) + 1;
                $distanceResults[] = [
                    'penduduk' => $penduduk,
                    'usia' => $usiaValue,
                    'tanggungan' => $tanggunganValue,
                    'kondisi_rumah' => $kondisiRumahValue,
                    'status_kepemilikan' => $statusKepemilikanValue,
                    'penghasilan' => $penghasilanValue,
                    'distances' => $distances,
                    'nearest_cluster' => $nearestCluster
                ];
                }
            } catch (\Exception $e) {
                return view('admin.centroid.index', compact('centroids', 'distanceResults', 'mappings'))
                    ->with('error', 'Terjadi kesalahan saat menghitung jarak: ' . $e->getMessage());
            }
        }

        return view('admin.centroid.index', compact('centroids', 'distanceResults', 'mappings'));
    }

    public function create()
    {
        return view('admin.centroid.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_centroid' => 'required|string|max:255',
            'usia' => 'required|numeric|min:0',
            'tanggungan_num' => 'required|numeric|min:0',
            'kondisi_rumah' => 'required|in:baik,cukup,kurang',
            'status_kepemilikan' => 'required|in:hak milik,sewa,numpang',
            'penghasilan_num' => 'required|numeric|min:0',
            'tahun' => 'required|integer',
            'periode' => 'required|integer',
            'keterangan' => 'nullable|string'
        ]);

        Centroid::create([
            'nama_centroid' => $request->nama_centroid,
            'usia' => (float) $request->usia,
            'tanggungan_num' => (int) $request->tanggungan_num,
            'kondisi_rumah' => $request->kondisi_rumah,
            'status_kepemilikan' => $request->status_kepemilikan,
            'penghasilan_num' => (float) $request->penghasilan_num,
            'tahun' => (int) $request->tahun,
            'periode' => (int) $request->periode,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('admin.centroid.index')
            ->with('success', 'Centroid berhasil ditambahkan');
    }

    public function update(Request $request, Centroid $centroid)
    {
        $request->validate([
            'nama_centroid' => 'required|string|max:255',
            'usia' => 'required|numeric|min:0',
            'tanggungan_num' => 'required|numeric|min:0',
            'kondisi_rumah' => 'required|in:baik,cukup,kurang',
            'status_kepemilikan' => 'required|in:hak milik,sewa,numpang',
            'penghasilan_num' => 'required|numeric|min:0',
            'tahun' => 'required|integer',
            'periode' => 'required|integer',
            'keterangan' => 'nullable|string'
        ]);

        $centroid->update([
            'nama_centroid' => $request->nama_centroid,
            'usia' => (float) $request->usia,
            'tanggungan_num' => (int) $request->tanggungan_num,
            'kondisi_rumah' => $request->kondisi_rumah,
            'status_kepemilikan' => $request->status_kepemilikan,
            'penghasilan_num' => (float) $request->penghasilan_num,
            'tahun' => (int) $request->tahun,
            'periode' => (int) $request->periode,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('admin.centroid.index')
            ->with('success', 'Centroid berhasil diperbarui');
    }

    public function destroy(Centroid $centroid)
    {
        $centroid->delete();
        return redirect()->route('admin.centroid.index')
            ->with('success', 'Centroid berhasil dihapus');
    }

    public function edit(Centroid $centroid)
    {
        return view('admin.centroid.edit', compact('centroid'));
    }
} 