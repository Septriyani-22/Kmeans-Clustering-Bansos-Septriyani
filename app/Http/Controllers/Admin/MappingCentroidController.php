<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MappingCentroid;
use Illuminate\Http\Request;

class MappingCentroidController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'data_ke' => 'required|exists:penduduk,id',
            'cluster' => 'required|in:C1,C2,C3',
            'nama_penduduk' => 'required|string',
            'usia' => 'required|numeric',
            'jumlah_tanggungan' => 'required|numeric',
            'kondisi_rumah' => 'required|numeric',
            'status_kepemilikan' => 'required|numeric',
            'jumlah_penghasilan' => 'required|numeric'
        ]);

        // Check if mapping already exists
        $existingMapping = MappingCentroid::where('data_ke', $request->data_ke)->first();
        if ($existingMapping) {
            return redirect()->back()->with('error', 'Data penduduk ini sudah memiliki mapping');
        }

        MappingCentroid::create([
            'data_ke' => $request->data_ke,
            'cluster' => $request->cluster,
            'nama_penduduk' => $request->nama_penduduk,
            'usia' => $request->usia,
            'jumlah_tanggungan' => $request->jumlah_tanggungan,
            'kondisi_rumah' => $request->kondisi_rumah,
            'status_kepemilikan' => $request->status_kepemilikan,
            'jumlah_penghasilan' => $request->jumlah_penghasilan
        ]);

        return redirect()->back()->with('success', 'Mapping berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'cluster' => 'required|in:C1,C2,C3'
        ]);

        $mapping = MappingCentroid::findOrFail($id);
        $mapping->update([
            'cluster' => $request->cluster
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $mapping = MappingCentroid::findOrFail($id);
        $mapping->delete();

        return redirect()->back()->with('success', 'Mapping berhasil dihapus');
    }
} 