<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use App\Exports\PendudukExport;
use App\Imports\PendudukImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use PDF;

class PendudukController extends Controller
{
    public function index(Request $request)
    {
        $query = Penduduk::query();

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nik', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('tahun', 'like', "%{$search}%")
                  ->orWhere('jenis_kelamin', 'like', "%{$search}%")
                  ->orWhere('usia', 'like', "%{$search}%")
                  ->orWhere('rt', 'like', "%{$search}%")
                  ->orWhere('tanggungan', 'like', "%{$search}%")
                  ->orWhere('kondisi_rumah', 'like', "%{$search}%")
                  ->orWhere('status_kepemilikan', 'like', "%{$search}%")
                  ->orWhere('penghasilan', 'like', "%{$search}%");
            });
        }

        // Filter by jenis kelamin
        if ($request->has('jenis_kelamin') && $request->jenis_kelamin !== '') {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        // Filter by RT
        if ($request->has('rt') && $request->rt !== '') {
            $query->where('rt', $request->rt);
        }

        // Filter by cluster
        if ($request->has('cluster') && $request->cluster !== '') {
            $query->where('cluster', $request->cluster);
        }

        // Filter by tahun
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        // Filter by usia min/max
        if ($request->filled('usia_min')) {
            $query->where('usia', '>=', $request->usia_min);
        }
        if ($request->filled('usia_max')) {
            $query->where('usia', '<=', $request->usia_max);
        }

        // Filter by tanggungan min/max
        if ($request->filled('tanggungan_min')) {
            $query->where('tanggungan', '>=', $request->tanggungan_min);
        }
        if ($request->filled('tanggungan_max')) {
            $query->where('tanggungan', '<=', $request->tanggungan_max);
        }

        // Filter by kondisi rumah
        if ($request->filled('kondisi_rumah')) {
            $query->where('kondisi_rumah', $request->kondisi_rumah);
        }

        // Filter by status kepemilikan
        if ($request->filled('status_kepemilikan')) {
            $query->where('status_kepemilikan', $request->status_kepemilikan);
        }

        // Filter by penghasilan min/max
        if ($request->filled('penghasilan_min')) {
            $query->where('penghasilan', '>=', $request->penghasilan_min);
        }
        if ($request->filled('penghasilan_max')) {
            $query->where('penghasilan', '<=', $request->penghasilan_max);
        }

        // Sorting
        if ($request->has('sort')) {
            $sort = $request->sort;
            $sorts = [
                'no', 'nik', 'nama', 'tahun', 'jenis_kelamin', 'usia', 'rt', 'tanggungan', 'kondisi_rumah', 'status_kepemilikan', 'penghasilan'
            ];
            foreach ($sorts as $field) {
                if ($sort === $field.'_asc') {
                    $query->orderBy($field, 'asc');
                    break;
                } elseif ($sort === $field.'_desc') {
                    $query->orderBy($field, 'desc');
                    break;
                }
            }
        } else {
            $query->orderBy('no', 'asc');
        }

        $penduduk = $query->paginate(20);
        return view('admin.penduduk.index', compact('penduduk'));
    }

    public function create()
    {
        return view('admin.penduduk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'no' => 'nullable|integer',
            'nik' => 'required|unique:penduduk,nik',
            'nama' => 'required',
            'tahun' => 'required|integer|min:2000|max:2100',
            'jenis_kelamin' => 'required|in:L,P',
            'usia' => 'required|integer|min:0',
            'rt' => 'required|integer|min:1',
            'tanggungan' => 'required|integer|min:0',
            'kondisi_rumah' => 'required|in:baik,cukup,kurang',
            'status_kepemilikan' => 'required|in:hak milik,numpang,sewa',
            'penghasilan' => 'required|numeric|min:0',
        ]);

        Penduduk::create($request->all());
        return redirect()->route('admin.penduduk.index')->with('success', 'Data penduduk berhasil ditambahkan!');
    }

    public function edit(Penduduk $penduduk)
    {
        return view('admin.penduduk.edit', compact('penduduk'));
    }

    public function update(Request $request, Penduduk $penduduk)
    {
        $request->validate([
            'no' => 'nullable|integer',
            'nik' => 'required|unique:penduduk,nik,' . $penduduk->id,
            'nama' => 'required',
            'tahun' => 'required|integer|min:2000|max:2100',
            'jenis_kelamin' => 'required|in:L,P',
            'usia' => 'required|integer|min:0',
            'rt' => 'required|integer|min:1',
            'tanggungan' => 'required|integer|min:0',
            'kondisi_rumah' => 'required|in:baik,cukup,kurang',
            'status_kepemilikan' => 'required|in:hak milik,numpang,sewa',
            'penghasilan' => 'required|numeric|min:0',
        ]);

        $penduduk->update($request->all());
        return redirect()->route('admin.penduduk.index')->with('success', 'Data penduduk berhasil diperbarui!');
    }

    public function destroy(Penduduk $penduduk)
    {
        $penduduk->delete();
        return redirect()->route('admin.penduduk.index')->with('success', 'Data penduduk berhasil dihapus!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new PendudukImport, $request->file('file'));
            return redirect()->route('admin.penduduk.index')->with('success', 'Data berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function export()
    {
        return Excel::download(new PendudukExport, 'data-penduduk.xlsx');
    }

    public function print()
    {
        $penduduk = Penduduk::orderBy('no')->get();
        $pdf = PDF::loadView('admin.penduduk.print', compact('penduduk'));
        return $pdf->stream('data-penduduk.pdf');
    }

    public function template()
    {
        return response()->download(public_path('templates/template-penduduk.xlsx'));
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

    public function autocomplete(Request $request)
    {
        $q = $request->get('q', '');
        $query = Penduduk::query();
        if ($q !== '') {
            $query->where(function($sub) use ($q) {
                $sub->where('nik', 'like', "%{$q}%")
                    ->orWhere('nama', 'like', "%{$q}%")
                    ->orWhere('tahun', 'like', "%{$q}%")
                    ->orWhere('jenis_kelamin', 'like', "%{$q}%")
                    ->orWhere('usia', 'like', "%{$q}%")
                    ->orWhere('rt', 'like', "%{$q}%")
                    ->orWhere('tanggungan', 'like', "%{$q}%")
                    ->orWhere('kondisi_rumah', 'like', "%{$q}%")
                    ->orWhere('status_kepemilikan', 'like', "%{$q}%")
                    ->orWhere('penghasilan', 'like', "%{$q}%");
            });
        }
        $results = $query->limit(10)->get();
        $suggestions = $results->map(function($item) {
            return [
                'display' => $item->nik.' | '.$item->nama.' | '.$item->tahun.' | '.$item->jenis_kelamin.' | '.$item->usia.' | '.$item->rt.' | '.$item->tanggungan.' | '.$item->kondisi_rumah.' | '.$item->status_kepemilikan.' | '.$item->penghasilan
            ];
        });
        return response()->json($suggestions);
    }
}
