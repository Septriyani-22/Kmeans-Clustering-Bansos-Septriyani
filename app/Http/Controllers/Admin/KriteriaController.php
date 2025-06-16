<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use App\Models\NilaiKriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KriteriaController extends Controller
{
    public function index()
    {
        $kriteria = Kriteria::with('nilaiKriteria')->get();
        return view('admin.kriteria.index', compact('kriteria'));
    }

    public function create()
    {
        return view('admin.kriteria.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipe_kriteria' => 'required|string|in:' . implode(',', Kriteria::getTipeKriteria()),
            'nama_kriteria' => 'required|string|max:255',
            'min' => 'required|numeric',
            'max' => 'required|numeric',
            'nilai' => 'required|numeric',
            'nama' => 'required|string|max:255'
        ]);

        Kriteria::create($request->all());

        return redirect()->route('admin.kriteria.index')
            ->with('success', 'Kriteria berhasil ditambahkan');
    }

    public function edit(Kriteria $kriteria)
    {
        $kriteria->load('nilaiKriteria');
        return view('admin.kriteria.edit', compact('kriteria'));
    }

    public function update(Request $request, Kriteria $kriteria)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'nilai' => 'required|array',
            'nilai.*.nama' => 'required|string|max:255',
            'nilai.*.nilai' => 'required|numeric|min:1',
            'nilai.*.nilai_min' => 'nullable|numeric',
            'nilai.*.nilai_max' => 'nullable|numeric'
        ]);

        DB::beginTransaction();
        try {
            // Update kriteria
            $kriteria->update([
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi
            ]);

            // Delete existing nilai kriteria
            $kriteria->nilaiKriteria()->delete();

            // Create new nilai kriteria
            foreach ($request->nilai as $nilai) {
                $kriteria->nilaiKriteria()->create([
                    'nama' => $nilai['nama'],
                    'nilai' => $nilai['nilai'],
                    'nilai_min' => $nilai['nilai_min'] ?? null,
                    'nilai_max' => $nilai['nilai_max'] ?? null
                ]);
            }

            DB::commit();
            return redirect()->route('admin.kriteria.index')
                ->with('success', 'Kriteria berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Kriteria $kriteria)
    {
        $kriteria->delete();
        return redirect()->route('admin.kriteria.index')
            ->with('success', 'Kriteria berhasil dihapus');
    }
}