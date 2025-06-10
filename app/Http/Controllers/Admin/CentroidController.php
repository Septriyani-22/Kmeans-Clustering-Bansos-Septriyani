<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Centroid;

class CentroidController extends Controller
{
    public function index()
    {
        $centroids = Centroid::orderBy('id')->get();
        return view('admin.centroid', compact('centroids'));
    }

    public function create()
    {
        return view('admin.centroid_create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_centroid.*' => 'required|string|max:255',
            'penghasilan_num.*' => 'required|integer|min:0',
            'tanggungan_num.*' => 'required|integer|min:0',
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
                    'penghasilan_num' => $request->penghasilan_num[$i],
                    'tanggungan_num' => $request->tanggungan_num[$i],
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