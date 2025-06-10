@extends('layouts.kepala_desa')
@section('title', 'Tambah Data Centroid')
@section('content')
<h2>Tambah Data Centroid (Input Kriteria Awal)</h2>
@if(session('success'))
    <div style="color:green;">{{ session('success') }}</div>
@endif
<form action="{{ route('kepala_desa.centroid.store') }}" method="POST">
    @csrf
    <table border="1" cellpadding="8" style="margin-bottom:16px;">
        <tr>
            <th>Nama Centroid</th>
            <th>Penghasilan (Numerik)</th>
            <th>Tanggungan (Numerik)</th>
            <th>Keterangan</th>
        </tr>
        @for($i=0;$i<3;$i++)
        <tr>
            <td><input type="text" name="nama_centroid[]" required></td>
            <td><input type="number" name="penghasilan_num[]" required></td>
            <td><input type="number" name="tanggungan_num[]" required></td>
            <td><input type="text" name="keterangan[]"></td>
        </tr>
        @endfor
    </table>
    <label>Tahun: <input type="number" name="tahun" value="{{ date('Y') }}" required></label>
    <label>Periode: <input type="number" name="periode" value="1" required></label>
    <button type="submit">Simpan Centroid</button>
</form>

@if(isset($centroids) && count($centroids))
    <h3>Daftar Centroid Saat Ini</h3>
    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Penghasilan</th>
            <th>Tanggungan</th>
            <th>Tahun</th>
            <th>Periode</th>
            <th>Keterangan</th>
            <th>Aksi</th>
        </tr>
        @foreach($centroids as $c)
        <tr>
            <td>{{ $c->id }}</td>
            <td>{{ $c->nama_centroid }}</td>
            <td>{{ $c->penghasilan_num }}</td>
            <td>{{ $c->tanggungan_num }}</td>
            <td>{{ $c->tahun }}</td>
            <td>{{ $c->periode }}</td>
            <td>{{ $c->keterangan }}</td>
            <td>
                <form action="{{ route('kepala_desa.centroid.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
@endif
@endsection 