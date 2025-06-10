<?php

namespace App\Http\Controllers\KepalaDesa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Centroid;

class CentroidController extends Controller
{
    public function index()
    {
        $centroids = Centroid::orderBy('id')->get();
        return view('kepala_desa.centroid', compact('centroids'));
    }

    public function create()
    {
        return view('kepala_desa.centroid_create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_centroid.*' => 'required',
            'penghasilan_num.*' => 'required|integer',
            'tanggungan_num.*' => 'required|integer',
            'tahun' => 'required|integer',
            'periode' => 'required|integer',
        ]);
        foreach ($request->nama_centroid as $i => $nama) {
            Centroid::create([
                'nama_centroid' => $nama,
                'penghasilan_num' => $request->penghasilan_num[$i],
                'tanggungan_num' => $request->tanggungan_num[$i],
                'tahun' => $request->tahun,
                'periode' => $request->periode,
                'keterangan' => $request->keterangan[$i] ?? null,
            ]);
        }
        return redirect()->route('kepala_desa.centroid.index')->with('success', 'Data centroid berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        Centroid::findOrFail($id)->delete();
        return redirect()->route('kepala_desa.centroid.index')->with('success', 'Data centroid berhasil dihapus!');
    }
} 