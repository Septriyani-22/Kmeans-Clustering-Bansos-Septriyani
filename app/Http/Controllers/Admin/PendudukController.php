<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use App\Exports\PendudukExport;
use App\Imports\PendudukImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class PendudukController extends Controller
{
    public function index(Request $request)
    {
        $query = Penduduk::query();

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nik', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%");
            });
        }

        // Filter
        if ($request->has('jenis_kelamin') && $request->jenis_kelamin != '') {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        if ($request->has('rt') && $request->rt != '') {
            $query->where('rt', $request->rt);
        }

        // Sort
        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction', 'asc');
        $query->orderBy($sort, $direction);

        $penduduks = $query->paginate(10);

        return view('admin.penduduk.index', compact('penduduks'));
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
            'status_kepemilikan' => 'required|in:hak milik,numpang,sewa',
        ]);

        try {
            Penduduk::create($request->all());
            return redirect()->route('admin.penduduk.index')->with('success', 'Data berhasil ditambah');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $penduduk = Penduduk::findOrFail($id);
            return view('admin.penduduk.edit', compact('penduduk'));
        } catch (\Exception $e) {
            return redirect()->route('admin.penduduk.index')->with('error', 'Data tidak ditemukan');
        }
    }

    public function update(Request $request, $id)
    {
        try {
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
                'status_kepemilikan' => 'required|in:hak milik,numpang,sewa',
            ]);

            $penduduk->update($request->all());
            return redirect()->route('admin.penduduk.index')->with('success', 'Data berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            Penduduk::destroy($id);
            return redirect()->route('admin.penduduk.index')->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            DB::beginTransaction();
            
            Excel::import(new PendudukImport, $request->file('file'));
            
            DB::commit();
            return redirect()->back()->with('success', 'Data berhasil diimport');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }

    public function export()
    {
        try {
            return Excel::download(new PendudukExport, 'data-penduduk.xlsx');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengexport data: ' . $e->getMessage());
        }
    }

    public function format()
    {
        try {
            return Excel::download(new PendudukExport, 'template-import-penduduk.xlsx');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengunduh template: ' . $e->getMessage());
        }
    }

    public function cetak()
    {
        $penduduks = Penduduk::all();
        return view('admin.penduduk.cetak', compact('penduduks'));
    }

    public function massUpdate(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:penduduks,id',
            'field' => 'required|string',
            'value' => 'required'
        ]);

        try {
            $field = $request->field;
            $value = $request->value;
            $ids = $request->ids;

            // Validasi field yang diizinkan untuk update massal
            $allowedFields = ['tahun', 'jenis_kelamin', 'rt', 'kondisi_rumah', 'status_kepemilikan'];
            if (!in_array($field, $allowedFields)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Field tidak diizinkan untuk update massal'
                ], 422);
            }

            // Update data
            Penduduk::whereIn('id', $ids)->update([$field => $value]);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function massDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:penduduks,id'
        ]);

        try {
            Penduduk::whereIn('id', $request->ids)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
