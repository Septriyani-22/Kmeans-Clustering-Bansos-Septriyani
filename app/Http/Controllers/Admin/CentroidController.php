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
        $centroids = Centroid::orderBy('id')->get();
        $penduduk = Penduduk::all();
        $hasil = [];

        // If no centroids exist, create initial centroids
        if ($centroids->isEmpty()) {
            // Get 3 random penduduk as initial centroids
            $initialCentroids = Penduduk::inRandomOrder()->take(3)->get();
            
            foreach ($initialCentroids as $index => $p) {
                Centroid::create([
                    'nama_centroid' => 'Cluster ' . ($index + 1),
                    'usia' => $p->usia,
                    'tanggungan_num' => $p->tanggungan,
                    'kondisi_rumah' => $p->kondisi_rumah,
                    'status_kepemilikan' => $p->status_kepemilikan,
                    'penghasilan_num' => $p->penghasilan,
                    'tahun' => date('Y'),
                    'periode' => 1
                ]);
            }
            
            $centroids = Centroid::orderBy('id')->get();
        }

        foreach ($penduduk as $p) {
            $jarak = [];
            foreach ($centroids as $c) {
                // Convert all values to float before calculation
                $jarak[] = sqrt(
                    pow((float)$p->usia - (float)($c->usia ?? 0), 2) +
                    pow((float)$p->tanggungan - (float)($c->tanggungan_num ?? 0), 2) +
                    pow((float)$p->kondisi_rumah - (float)($c->kondisi_rumah ?? 0), 2) +
                    pow((float)$p->status_kepemilikan - (float)($c->status_kepemilikan ?? 0), 2) +
                    pow((float)$p->penghasilan - (float)($c->penghasilan_num ?? 0), 2)
                );
            }
            $hasil[] = [
                'jarak1' => $jarak[0] ?? 0,
                'jarak2' => $jarak[1] ?? 0,
                'jarak3' => $jarak[2] ?? 0,
                'cluster' => array_search(min($jarak), $jarak) + 1
            ];
        }

        return view('admin.centroid', compact('centroids', 'hasil'));
    }

    public function create()
    {
        return view('admin.centroid_create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_centroid.*' => 'required|string|max:255',
            'usia.*' => 'required|numeric|min:0',
            'tanggungan_num.*' => 'required|numeric|min:0',
            'kondisi_rumah.*' => 'required|in:baik,cukup,kurang',
            'status_kepemilikan.*' => 'required|in:hak milik,numpang,sewa',
            'penghasilan_num.*' => 'required|numeric|min:0',
            'tahun' => 'required|integer|min:2000|max:2100',
            'periode' => 'required|integer|min:1',
            'keterangan.*' => 'nullable|string|max:255',
        ]);

        try {
            foreach ($request->nama_centroid as $i => $nama) {
                // Clean and format the nama_centroid
                $nama = trim($nama);
                
                // If the name is just a number, format it as "Cluster X"
                if (is_numeric($nama)) {
                    $nama = 'Cluster ' . $nama;
                }
                // If it doesn't start with "Cluster", add it
                elseif (!str_starts_with($nama, 'Cluster')) {
                    $nama = 'Cluster ' . $nama;
                }

                // Validate that the name is not empty after formatting
                if (empty($nama)) {
                    throw new \Exception('Nama centroid tidak boleh kosong');
                }

                Centroid::create([
                    'nama_centroid' => $nama,
                    'usia' => (float)$request->usia[$i],
                    'tanggungan_num' => (float)$request->tanggungan_num[$i],
                    'kondisi_rumah' => $request->kondisi_rumah[$i],
                    'status_kepemilikan' => $request->status_kepemilikan[$i],
                    'penghasilan_num' => (float)$request->penghasilan_num[$i],
                    'tahun' => $request->tahun,
                    'periode' => $request->periode,
                    'keterangan' => $request->keterangan[$i] ?? null,
                ]);
            }

            return redirect()->route('admin.centroid.index')
                ->with('success', 'Data centroid berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            Centroid::findOrFail($id)->delete();
            return redirect()->route('admin.centroid.index')
                ->with('success', 'Data centroid berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
} 