<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MappingCentroid;
use App\Models\Penduduk;
use App\Models\Centroid;

class MappingCentroidController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mappings = MappingCentroid::with(['penduduk', 'centroid'])->get();
        return view('admin.mapping_centroid.index', compact('mappings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $penduduks = Penduduk::all();
        $centroids = Centroid::all();
        return view('admin.mapping_centroid.create', compact('penduduks', 'centroids'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'penduduk_id' => 'required|exists:penduduks,id',
            'centroid_id' => 'required|exists:centroids,id',
            'jarak_euclidean' => 'required|numeric',
            'cluster' => 'required|integer|min:1'
        ]);

        MappingCentroid::create($request->all());

        return redirect()->route('admin.centroid.index')
            ->with('success', 'Mapping centroid berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MappingCentroid $mapping)
    {
        $penduduks = Penduduk::all();
        $centroids = Centroid::all();
        
        return view('admin.mapping_centroid.edit', compact('mapping', 'penduduks', 'centroids'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MappingCentroid $mapping)
    {
        $request->validate([
            'penduduk_id' => 'required|exists:penduduk,id',
            'centroid_id' => 'required|exists:centroids,id',
            'jarak_euclidean' => 'required|numeric',
            'cluster' => 'required|integer|min:1'
        ]);

        $mapping->update($request->all());

        return redirect()->route('admin.centroid.index')
            ->with('success', 'Mapping centroid berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MappingCentroid $mapping)
    {
        $mapping->delete();
        return redirect()->route('admin.centroid.index')
            ->with('success', 'Mapping centroid berhasil dihapus');
    }

    public function storeFromDistance(Request $request)
    {
        $request->validate([
            'penduduk_id' => 'required|exists:penduduks,id',
            'centroid_id' => 'required|exists:centroids,id',
            'jarak_euclidean' => 'required|numeric',
            'cluster' => 'required|integer|min:1'
        ]);

        MappingCentroid::create($request->all());

        return redirect()->route('admin.centroid.index')
            ->with('success', 'Mapping centroid berhasil ditambahkan');
    }
}
