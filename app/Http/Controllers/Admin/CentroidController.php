<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Centroid;
use App\Models\Penduduk;

class CentroidController extends Controller
{
    public function index()
    {
        $centroids = Centroid::all();
        $penduduks = Penduduk::all();
        $distanceResults = [];

        // Only calculate distances if there are centroids
        if ($centroids->isNotEmpty()) {
            foreach ($penduduks as $penduduk) {
                $distances = [];
                foreach ($centroids as $centroid) {
                    $dist = sqrt(
                        pow($penduduk->usia - $centroid->usia, 2) +
                        pow($penduduk->tanggungan - $centroid->tanggungan_num, 2) +
                        pow($this->convertKondisiRumah($penduduk->kondisi_rumah) - $this->convertKondisiRumah($centroid->kondisi_rumah), 2) +
                        pow($this->convertStatusKepemilikan($penduduk->status_kepemilikan) - $this->convertStatusKepemilikan($centroid->status_kepemilikan), 2) +
                        pow($penduduk->penghasilan - $centroid->penghasilan_num, 2)
                    );
                    $distances[] = $dist;
                }
                $min = min($distances);
                $nearestCluster = array_search($min, $distances) + 1;
                $distanceResults[] = [
                    'penduduk' => $penduduk,
                    'distances' => $distances,
                    'nearest_cluster' => $nearestCluster
                ];
            }
        }

        return view('admin.centroid.index', compact('centroids', 'distanceResults'));
    }

    private function convertKondisiRumah($kondisi)
    {
        return match($kondisi) {
            'baik' => 3,
            'cukup' => 2,
            'kurang' => 1,
            default => 0
        };
    }
    private function convertStatusKepemilikan($status)
    {
        return match($status) {
            'hak milik' => 3,
            'sewa' => 2,
            'numpang' => 1,
            default => 0
        };
    }

    public function create()
    {
        return view('admin.centroid_create');
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
} 