<?php

namespace App\Http\Controllers\KepalaDesa;

use App\Http\Controllers\Controller;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PendudukImport;
use App\Exports\PendudukExport;

class PendudukController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $penduduks = Penduduk::when($search, function($query, $search) {
            return $query->where('nama', 'like', "%$search%")
                         ->orWhere('nik', 'like', "%$search%");
        })->paginate(10);

        return view('kepala_desa.penduduk.index', compact('penduduks', 'search'));
    }

    public function create()
    {
        return view('kepala_desa.penduduk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|unique:penduduks,nik',
            'nama' => 'required',
            'tahun' => 'required|numeric',
            'periode' => 'required',
            'kps' => 'required',
            'status_perkawinan' => 'required',
            'umur' => 'required|numeric',
            'jumlah_tanggungan' => 'required|numeric',
            'pekerjaan' => 'nullable|string',
            'penghasilan' => 'nullable|numeric',
            'status_kepemilikan_rumah' => 'nullable|string',
            'luas_bangunan' => 'nullable|numeric',
            'kondisi_rumah' => 'nullable|string',
            'jaringan_listrik' => 'nullable|string',
            'sumber_air' => 'nullable|string',
            'kepemilikan_kendaraan' => 'nullable|string',
        ]);

        Penduduk::create($request->all());
        return redirect()->route('kepala_desa.penduduk.index')->with('success', 'Data berhasil ditambah');
    }

    public function edit($id)
    {
        $penduduk = Penduduk::findOrFail($id);
        return view('kepala_desa.penduduk.edit', compact('penduduk'));
    }

    public function update(Request $request, $id)
    {
        $penduduk = Penduduk::findOrFail($id);

        $request->validate([
            'nik' => 'required|unique:penduduks,nik,' . $penduduk->id,
            'nama' => 'required',
            'tahun' => 'required|numeric',
            'periode' => 'required',
            'kps' => 'required',
            'status_perkawinan' => 'required',
            'umur' => 'required|numeric',
            'jumlah_tanggungan' => 'required|numeric',
            'pekerjaan' => 'nullable|string',
            'penghasilan' => 'nullable|numeric',
            'status_kepemilikan_rumah' => 'nullable|string',
            'luas_bangunan' => 'nullable|numeric',
            'kondisi_rumah' => 'nullable|string',
            'jaringan_listrik' => 'nullable|string',
            'sumber_air' => 'nullable|string',
            'kepemilikan_kendaraan' => 'nullable|string',
        ]);

        $penduduk->update($request->all());
        return redirect()->route('kepala_desa.penduduk.index')->with('success', 'Data berhasil diperbarui');
    }

    public function destroy($id)
    {
        Penduduk::destroy($id);
        return redirect()->route('kepala_desa.penduduk.index')->with('success', 'Data berhasil dihapus');
    }

    public function import(Request $request)
    {
        \Log::info('Import request masuk');
        
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);
        
        try {
            Excel::import(new PendudukImport, $request->file('file'));
            \Log::info('Import berhasil');
            return redirect()->route('kepala_desa.penduduk.index')->with('success', 'Data Berhasil Di Import');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            \Log::error('Validasi gagal saat import', ['failures' => $failures]);
            return redirect()->route('kepala_desa.penduduk.index')->with('error', 'Data gagal diimport, cek validasi.');
        } catch (\Exception $e) {
            \Log::error('Exception saat import', ['message' => $e->getMessage()]);
            return redirect()->route('kepala_desa.penduduk.index')->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }
            public function export()
    {
        return Excel::download(new PendudukExport, 'penduduk.xlsx');
    }

    public function format()
    {
        return response()->download(public_path('format_penduduk.xlsx'));
    }

    public function cetak()
    {
        $penduduks = Penduduk::all();
        return view('kepala_desa.penduduk.cetak', compact('penduduks'));
    }
        
}
