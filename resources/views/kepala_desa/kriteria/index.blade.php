@extends('layouts.kepala_desa')

@section('title', 'Data Kriteria - BANSOS KMEANS')

@section('content')
<div style="background:#fff; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,0.07); padding:32px 40px; max-width:900px; margin:0 auto;">
    <h1 style="font-size:2rem; color:#888fa6; font-weight:400; margin-bottom:18px;">Data Kriteria</h1>
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
        <a href="{{ route('kepala_desa.kriteria.create') }}" style="background:#22c55e; color:#fff; border:none; border-radius:6px; padding:10px 18px; font-size:1rem; cursor:pointer; text-decoration:none;">Tambah Data</a>
        <div>
            <form action="{{ route('kepala_desa.kriteria.index') }}" method="GET">
                <label for="search" style="color:#888fa6; font-size:1rem; margin-right:8px;">Search:</label>
                <input type="text" id="search" name="search" style="padding:7px 12px; border:1px solid #d1d5db; border-radius:6px; font-size:1rem;" value="{{ request('search') }}">
                <button type="submit" style="background:#2563eb; color:#fff; border:none; border-radius:6px; padding:7px 12px; margin-left:8px; font-size:1rem; cursor:pointer;">Cari</button>
            </form>
        </div>
    </div>
    <table style="width:100%; border-collapse:collapse; margin-bottom:18px; text-align:center;">
        <thead>
            <tr style="background:#f4f6fa; color:#888fa6;">
                <th style="padding:10px; border-bottom:1px solid #e5e7eb;">Nama Kriteria</th>
                <th style="padding:10px; border-bottom:1px solid #e5e7eb;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($kriteria as $item)
                <tr>
                    <td style="padding:10px; border-bottom:1px solid #e5e7eb;">{{ $item->nama_kriteria }}</td>
                    <td style="padding:10px; border-bottom:1px solid #e5e7eb;">
                        <a href="{{ route('kepala_desa.kriteria.edit', $item->id) }}" style="background:#2563eb; color:#fff; border:none; border-radius:4px; padding:6px 14px; margin-right:6px; cursor:pointer; text-decoration:none;">Ubah</a>
                        <form action="{{ route('kepala_desa.kriteria.destroy', $item->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background:#ef4444; color:#fff; border:none; border-radius:4px; padding:6px 14px; cursor:pointer;" onclick="return confirm('Are you sure you want to delete this kriteria?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" style="padding:10px; text-align:center; color:#888fa6;">No kriteria found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <div style="color:#888fa6; font-size:0.95rem;">
            Showing {{ $kriteria->firstItem() }} to {{ $kriteria->lastItem() }} of {{ $kriteria->total() }} entries
        </div>
        <div>
            {{ $kriteria->links('pagination::simple-tailwind') }}
        </div>
    </div>
</div>
@endsection