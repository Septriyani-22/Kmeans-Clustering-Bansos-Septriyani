<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use App\Models\NilaiKriteria;
use Illuminate\Http\Request;

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
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:50|unique:kriteria',
            'deskripsi' => 'nullable|string',
            'nilai' => 'required|array',
            'nilai.*.nama' => 'required|string|max:255',
            'nilai.*.nilai' => 'required|integer|min:1',
            'nilai.*.nilai_min' => 'nullable|numeric',
            'nilai.*.nilai_max' => 'nullable|numeric',
            'nilai.*.keterangan' => 'nullable|string|max:255',
        ]);

        $kriteria = Kriteria::create([
            'nama' => $request->nama,
            'kode' => $request->kode,
            'deskripsi' => $request->deskripsi,
        ]);

        foreach ($request->nilai as $nilai) {
            $kriteria->nilaiKriteria()->create([
                'nama' => $nilai['nama'],
                'nilai' => $nilai['nilai'],
                'nilai_min' => $nilai['nilai_min'] ?? null,
                'nilai_max' => $nilai['nilai_max'] ?? null,
                'keterangan' => $nilai['keterangan'] ?? null,
            ]);
        }

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
            'kode' => 'required|string|max:50|unique:kriteria,kode,' . $kriteria->id,
            'deskripsi' => 'nullable|string',
            'nilai' => 'required|array',
            'nilai.*.nama' => 'required|string|max:255',
            'nilai.*.nilai' => 'required|integer|min:1',
            'nilai.*.nilai_min' => 'nullable|numeric',
            'nilai.*.nilai_max' => 'nullable|numeric',
            'nilai.*.keterangan' => 'nullable|string|max:255',
        ]);

        $kriteria->update([
            'nama' => $request->nama,
            'kode' => $request->kode,
            'deskripsi' => $request->deskripsi,
        ]);

        // Hapus nilai kriteria yang lama
        $kriteria->nilaiKriteria()->delete();

        // Tambah nilai kriteria yang baru
        foreach ($request->nilai as $nilai) {
            $kriteria->nilaiKriteria()->create([
                'nama' => $nilai['nama'],
                'nilai' => $nilai['nilai'],
                'nilai_min' => $nilai['nilai_min'] ?? null,
                'nilai_max' => $nilai['nilai_max'] ?? null,
                'keterangan' => $nilai['keterangan'] ?? null,
            ]);
        }

        return redirect()->route('admin.kriteria.index')
            ->with('success', 'Kriteria berhasil diperbarui');
    }

    public function destroy(Kriteria $kriteria)
    {
        $kriteria->delete();
        return redirect()->route('admin.kriteria.index')
            ->with('success', 'Kriteria berhasil dihapus');
    }
}