@extends('layouts.kepala_desa')
@section('title', 'Laporan Hasil')
@section('content')

<h1>Laporan Hasil K-Means</h1>

<a href="{{ route('kepala_desa.laporanhasil.export') }}" 
   style="background:#22c55e; color:#fff; padding:6px 12px; border-radius:4px; text-decoration:none; display:inline-block; margin-bottom:1rem;">
   Export Excel
</a>

<table style="border-collapse: collapse; width: 100%;" border="1" cellpadding="4" cellspacing="0">
    <thead style="background-color: #f3f4f6;">
        <tr>
            <th style="padding: 8px; text-align:left;">Cluster</th>
            <th style="padding: 8px; text-align:left;">Jumlah Penerima</th>
        </tr>
    </thead>
    <tbody>
        @forelse($summary as $cluster => $total)
        <tr>
            <td style="padding: 6px;">{{ $cluster }}</td>
            <td style="padding: 6px;">{{ $total }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="2" style="text-align:center; padding: 6px;">Data tidak ditemukan</td>
        </tr>
        @endforelse
    </tbody>
</table>

@endsection