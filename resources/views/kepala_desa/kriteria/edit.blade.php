@extends('layouts.kepala_desa')

@section('title', 'Edit Kriteria - BANSOS KMEANS')

@section('content')
<div style="background:#fff; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,0.07); padding:32px 40px; max-width:900px; margin:0 auto;">
    <h1 style="font-size:2rem; color:#888fa6; font-weight:400; margin-bottom:18px;">Edit Kriteria</h1>
    <form action="{{ route('kepala_desa.kriteria.update', $kriteria->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div style="margin-bottom:18px;">
            <label for="nama_kriteria" style="color:#888fa6; font-size:1rem;">Nama Kriteria:</label>
            <input type="text" id="nama_kriteria" name="nama_kriteria" value="{{ old('nama_kriteria', $kriteria->nama_kriteria) }}" style="padding:7px 12px; border:1px solid #d1d5db; border-radius:6px; font-size:1rem; width:100%;" required>
            @error('nama_kriteria')
                <p style="color:#ef4444; font-size:0.875rem; margin-top:4px;">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit" style="background:#22c55e; color:#fff; border:none; border-radius:6px; padding:10px 18px; font-size:1rem; cursor:pointer;">Update</button>
        <a href="{{ route('kepala_desa.kriteria.index') }}" style="background:#888fa6; color:#fff; border:none; border-radius:6px; padding:10px 18px; font-size:1rem; text-decoration:none; margin-left:10px; display:inline-block;">Batal</a>
    </form>
</div>
@endsection