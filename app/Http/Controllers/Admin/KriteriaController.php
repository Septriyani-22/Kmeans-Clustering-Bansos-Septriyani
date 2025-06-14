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
            'tipe_kriteria' => 'required|string|in:' . implode(',', Kriteria::getTipeKriteria()),
            'nama_kriteria' => 'required|string|max:255',
            'min' => 'required|numeric',
            'max' => 'required|numeric',
            'nilai' => 'required|numeric',
            'nama' => 'required|string|max:255'
        ]);

        $kriteria->update($request->all());

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