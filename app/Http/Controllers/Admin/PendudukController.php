<?php

namespace App\Http\Controllers\Admin;

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
        $jenis_kelamin = $request->input('jenis_kelamin');
        $rt = $request->input('rt');

        $penduduks = Penduduk::query()
            ->when($search, function($query, $search) {
                $query->where('nama', 'like', "%$search%")
                      ->orWhere('nik', 'like', "%$search%");
            })
            ->when($jenis_kelamin, function($query, $jenis_kelamin) {
                $query->where('jenis_kelamin', $jenis_kelamin);
            })
            ->when($rt, function($query, $rt) {
                $query->where('rt', $rt);
            })
            ->paginate(10);

        return view('admin.penduduk.index', compact('penduduks', 'search'));
    }

    public function create()
    {
        return view('admin.penduduk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|unique:penduduks,nik',
            'nama' => 'required',
            'tahun' => 'required|numeric',
            'jenis_kelamin' => 'required|in:L,P',
            'usia' => 'required|numeric|min:0',
            'rt' => 'required|numeric|min:1',
            'tanggungan' => 'required|numeric|min:1',
            'penghasilan' => 'required|numeric|min:0',
            'kondisi_rumah' => 'required|in:kurang,cukup,baik',
            'status_kepemilikan' => 'required|in:hak milik,Numpang',
        ]);

        Penduduk::create($request->all());
        return redirect()->route('admin.penduduk.index')->with('success', 'Data berhasil ditambah');
    }

    public function edit($id)
    {
        $penduduk = Penduduk::findOrFail($id);
        return view('admin.penduduk.edit', compact('penduduk'));
    }

    public function update(Request $request, $id)
    {
        $penduduk = Penduduk::findOrFail($id);
        
        $request->validate([
            'nik' => 'required|unique:penduduks,nik,' . $penduduk->id,
            'nama' => 'required',
            'tahun' => 'required|numeric',
            'jenis_kelamin' => 'required|in:L,P',
            'usia' => 'required|numeric|min:0',
            'rt' => 'required|numeric|min:1',
            'tanggungan' => 'required|numeric|min:1',
            'penghasilan' => 'required|numeric|min:0',
            'kondisi_rumah' => 'required|in:kurang,cukup,baik',
            'status_kepemilikan' => 'required|in:hak milik,Numpang',
        ]);

        $penduduk->update($request->all());
        return redirect()->route('admin.penduduk.index')->with('success', 'Data berhasil diperbarui');
    }

    public function destroy($id)
    {
        Penduduk::destroy($id);
        return redirect()->route('admin.penduduk.index')->with('success', 'Data berhasil dihapus');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xlsx,xls'
        ]);
        Excel::import(new PendudukImport, $request->file('file'));
        return redirect()->route('admin.penduduk.index')->with('success', 'Data berhasil diimport!');
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
        return view('admin.penduduk.cetak', compact('penduduks'));
    }
}
