<?php

namespace App\Http\Controllers\KepalaDesa;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KriteriaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $kriteria = Kriteria::query()
            ->when($search, fn($query) => $query->where('nama_kriteria', 'like', "%{$search}%"))
            ->paginate(6)
            ->appends(['search' => $search]);

        return view('kepala_desa.kriteria.index', compact('kriteria'));
    }

    public function create()
    {
        return view('kepala_desa.kriteria.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kriteria' => 'required|string|max:255|unique:kriteria',
        ]);

        Log::info('Data to create: ' . json_encode($validated));
        $kriteria = Kriteria::create($validated);
        Log::info('Created kriteria ID: ' . $kriteria->id);

        return redirect()->route('kepala_desa.kriteria.index')->with('success', 'Kriteria added successfully.');
    }

    public function edit(Kriteria $kriteria)
    {
        return view('kepala_desa.kriteria.edit', compact('kriteria'));
    }

    public function update(Request $request, Kriteria $kriteria)
    {
        $validated = $request->validate([
            'nama_kriteria' => 'required|string|max:255|unique:kriteria,nama_kriteria,' . $kriteria->id,
        ]);

        Log::info('Data to update: ' . json_encode($validated));
        $kriteria->update($validated);
        Log::info('Updated kriteria ID: ' . $kriteria->id);

        return redirect()->route('kepala_desa.kriteria.index')->with('success', 'Kriteria berhasil diubah.');
    }

    public function destroy(Kriteria $kriteria)
    {
        $kriteria->delete();

        return redirect()->route('kepala_desa.kriteria.index')->with('success', 'Kriteria berhasil dihapus.');
    }
}