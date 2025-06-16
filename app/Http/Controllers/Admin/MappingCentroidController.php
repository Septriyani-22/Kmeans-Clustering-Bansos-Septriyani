<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MappingCentroid;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MappingCentroidController extends Controller
{
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

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

            // Create new mapping
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

            DB::commit();
            return redirect()->back()->with('success', 'Mapping berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'cluster' => 'required|in:C1,C2,C3'
            ]);

            $mapping = MappingCentroid::findOrFail($id);
            $mapping->update([
                'cluster' => $request->cluster
            ]);

            DB::commit();
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $mapping = MappingCentroid::findOrFail($id);
            $mapping->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Mapping berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function getPendudukData($id)
    {
        try {
            $penduduk = Penduduk::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => [
                    'nama' => $penduduk->nama,
                    'usia' => $penduduk->usia,
                    'tanggungan' => $penduduk->tanggungan,
                    'kondisi_rumah' => $penduduk->kondisi_rumah,
                    'status_kepemilikan' => $penduduk->status_kepemilikan,
                    'penghasilan' => $penduduk->penghasilan
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
} 