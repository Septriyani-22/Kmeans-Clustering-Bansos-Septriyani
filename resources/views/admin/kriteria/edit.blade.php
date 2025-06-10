@extends('layouts.admin')

@section('title', 'Edit Kriteria - BANSOS KMEANS')

@section('content')
<div style="background:#fff; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,0.07); padding:24px; margin:0 auto;">
    <h1 style="font-size:2rem; color:#888fa6; font-weight:400; margin-bottom:18px;">Edit Kriteria</h1>

    <form action="{{ route('admin.kriteria.update', $kriteria->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div style="margin-bottom:18px;">
            <label style="display:block; margin-bottom:8px; color:#4b5563;">Nama Kriteria</label>
            <input type="text" name="nama_kriteria" value="{{ old('nama_kriteria', $kriteria->nama_kriteria) }}" required style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px; font-size:1rem;">
            @error('nama_kriteria')
                <p style="color:#ef4444; font-size:0.875rem; margin-top:4px;">{{ $message }}</p>
            @enderror
        </div>

        <div style="margin-bottom:18px;">
            <label style="display:block; margin-bottom:8px; color:#4b5563;">Nilai</label>
            <input type="number" name="nilai" value="{{ old('nilai', $kriteria->nilai) }}" required style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px; font-size:1rem;">
            @error('nilai')
                <p style="color:#ef4444; font-size:0.875rem; margin-top:4px;">{{ $message }}</p>
            @enderror
        </div>

        <div style="margin-bottom:18px;">
            <label style="display:block; margin-bottom:8px; color:#4b5563;">Keterangan</label>
            <textarea name="keterangan" rows="3" style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px; font-size:1rem;">{{ old('keterangan', $kriteria->keterangan) }}</textarea>
            @error('keterangan')
                <p style="color:#ef4444; font-size:0.875rem; margin-top:4px;">{{ $message }}</p>
            @enderror
        </div>

        <div style="margin-bottom:18px;">
            <label style="display:flex; align-items:center; gap:8px; color:#4b5563;">
                <input type="checkbox" name="is_aktif" value="1" {{ old('is_aktif', $kriteria->is_aktif) ? 'checked' : '' }}>
                <span>Aktif</span>
            </label>
        </div>

        <div style="display:flex; gap:8px;">
            <button type="submit" style="background:#22c55e; color:#fff; border:none; border-radius:6px; padding:10px 18px; font-size:1rem; cursor:pointer;">Update</button>
            <a href="{{ route('admin.kriteria.index') }}" style="background:#888fa6; color:#fff; border:none; border-radius:6px; padding:10px 18px; font-size:1rem; text-decoration:none;">Batal</a>
        </div>
    </form>
</div>
@endsection