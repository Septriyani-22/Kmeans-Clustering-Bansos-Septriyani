@extends('layouts.admin')
@section('title', 'Tambah Data Centroid')
@section('content')
<div style="background:#fff; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,0.07); padding:24px; margin:0 auto;">
    <h1 style="font-size:2rem; color:#888fa6; font-weight:400; margin-bottom:18px;">Tambah Data Centroid</h1>

    @if(session('success'))
        <div style="background:#dcfce7; color:#166534; border:1px solid #bbf7d0; padding:12px 16px; border-radius:6px; margin-bottom:18px;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background:#fee2e2; color:#991b1b; border:1px solid #fecaca; padding:12px 16px; border-radius:6px; margin-bottom:18px;">
            <ul style="margin:0; padding-left:20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.centroid.store') }}" method="POST">
        @csrf
        <div style="overflow-x:auto; margin-bottom:18px;">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#f4f6fa; color:#888fa6;">
                        <th style="padding:12px; text-align:left;">Nama Centroid</th>
                        <th style="padding:12px; text-align:left;">Penghasilan (Numerik)</th>
                        <th style="padding:12px; text-align:left;">Tanggungan (Numerik)</th>
                        <th style="padding:12px; text-align:left;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @for($i=0;$i<3;$i++)
                    <tr style="border-bottom:1px solid #e5e7eb;">
                        <td style="padding:12px;">
                            <input type="text" name="nama_centroid[]" value="{{ old('nama_centroid.'.$i) }}" required 
                                style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px; font-size:1rem;"
                                placeholder="Contoh: Cluster 1">
                        </td>
                        <td style="padding:12px;">
                            <input type="number" name="penghasilan_num[]" value="{{ old('penghasilan_num.'.$i) }}" required 
                                style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px; font-size:1rem;"
                                placeholder="Contoh: 3000000">
                        </td>
                        <td style="padding:12px;">
                            <input type="number" name="tanggungan_num[]" value="{{ old('tanggungan_num.'.$i) }}" required 
                                style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px; font-size:1rem;"
                                placeholder="Contoh: 3">
                        </td>
                        <td style="padding:12px;">
                            <input type="text" name="keterangan[]" value="{{ old('keterangan.'.$i) }}"
                                style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px; font-size:1rem;"
                                placeholder="Contoh: Kelompok dengan penghasilan rendah">
                        </td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        <div style="display:flex; gap:16px; margin-bottom:18px;">
            <div>
                <label style="display:block; margin-bottom:8px; color:#4b5563;">Tahun</label>
                <input type="number" name="tahun" value="{{ old('tahun', date('Y')) }}" required 
                    style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px; font-size:1rem;">
            </div>
            <div>
                <label style="display:block; margin-bottom:8px; color:#4b5563;">Periode</label>
                <input type="number" name="periode" value="{{ old('periode', 1) }}" required 
                    style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px; font-size:1rem;">
            </div>
        </div>

        <div style="display:flex; gap:8px;">
            <button type="submit" style="background:#22c55e; color:#fff; border:none; border-radius:6px; padding:10px 18px; font-size:1rem; cursor:pointer;">Simpan Centroid</button>
            <a href="{{ route('admin.centroid.index') }}" style="background:#888fa6; color:#fff; border:none; border-radius:6px; padding:10px 18px; font-size:1rem; text-decoration:none;">Batal</a>
        </div>
    </form>

    @if(isset($centroids) && count($centroids))
        <h2 style="font-size:1.5rem; color:#888fa6; font-weight:400; margin:32px 0 18px;">Daftar Centroid Saat Ini</h2>
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#f4f6fa; color:#888fa6;">
                        <th style="padding:12px; text-align:left;">ID</th>
                        <th style="padding:12px; text-align:left;">Nama</th>
                        <th style="padding:12px; text-align:left;">Penghasilan</th>
                        <th style="padding:12px; text-align:left;">Tanggungan</th>
                        <th style="padding:12px; text-align:left;">Tahun</th>
                        <th style="padding:12px; text-align:left;">Periode</th>
                        <th style="padding:12px; text-align:left;">Keterangan</th>
                        <th style="padding:12px; text-align:left;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($centroids as $c)
                    <tr style="border-bottom:1px solid #e5e7eb;">
                        <td style="padding:12px;">{{ $c->id }}</td>
                        <td style="padding:12px;">{{ $c->nama_centroid }}</td>
                        <td style="padding:12px;">{{ $c->penghasilan_formatted }}</td>
                        <td style="padding:12px;">{{ $c->tanggungan_num }}</td>
                        <td style="padding:12px;">{{ $c->tahun }}</td>
                        <td style="padding:12px;">{{ $c->periode }}</td>
                        <td style="padding:12px;">{{ $c->keterangan }}</td>
                        <td style="padding:12px;">
                            <form action="{{ route('admin.centroid.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background:#ef4444; color:#fff; border:none; border-radius:6px; padding:6px 12px; font-size:0.875rem; cursor:pointer;">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection 