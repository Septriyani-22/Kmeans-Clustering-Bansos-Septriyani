@extends('layouts.admin')
@section('title', 'Centroid')
@section('content')
<div style="background:#fff; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,0.07); padding:24px; margin:0 auto;">
    <h1 style="font-size:2rem; color:#888fa6; font-weight:400; margin-bottom:18px;">Data Centroid</h1>

    @if(session('success'))
        <div style="background:#dcfce7; color:#166534; border:1px solid #bbf7d0; padding:12px 16px; border-radius:6px; margin-bottom:18px;">
            {{ session('success') }}
        </div>
    @endif

    <div style="display:flex; justify-content:flex-end; margin-bottom:18px;">
        <a href="{{ route('admin.centroid.create') }}" style="background:#22c55e; color:#fff; border:none; border-radius:6px; padding:10px 18px; font-size:1rem; cursor:pointer; text-decoration:none;">+ Tambah Centroid</a>
    </div>

    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; margin-top:18px;">
            <thead>
                <tr style="background:#f4f6fa; color:#888fa6;">
                    <th style="padding:12px; text-align:left;">No</th>
                    <th style="padding:12px; text-align:left;">Nama Centroid</th>
                    <th style="padding:12px; text-align:left;">Penghasilan</th>
                    <th style="padding:12px; text-align:left;">Tanggungan</th>
                    <th style="padding:12px; text-align:left;">Tahun</th>
                    <th style="padding:12px; text-align:left;">Periode</th>
                    <th style="padding:12px; text-align:left;">Keterangan</th>
                    <th style="padding:12px; text-align:left;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($centroids as $centroid)
                <tr style="border-bottom:1px solid #e5e7eb;">
                    <td style="padding:12px;">{{ $loop->iteration }}</td>
                    <td style="padding:12px;">{{ $centroid->nama_centroid }}</td>
                    <td style="padding:12px;">{{ $centroid->penghasilan_num }}</td>
                    <td style="padding:12px;">{{ $centroid->tanggungan_num }}</td>
                    <td style="padding:12px;">{{ $centroid->tahun }}</td>
                    <td style="padding:12px;">{{ $centroid->periode }}</td>
                    <td style="padding:12px;">{{ $centroid->keterangan }}</td>
                    <td style="padding:12px;">
                        <a href="{{ route('admin.centroid.edit', $centroid->id) }}" style="background:#2563eb; color:#fff; border:none; border-radius:4px; padding:6px 14px; margin-right:6px; cursor:pointer; text-decoration:none;">Edit</a>
                        <form action="{{ route('admin.centroid.destroy', $centroid->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background:#ef4444; color:#fff; border:none; border-radius:4px; padding:6px 14px; cursor:pointer;" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="padding:12px; text-align:center; color:#888fa6;">Tidak ada data centroid</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection 