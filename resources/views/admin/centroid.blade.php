@extends('layouts.admin')
@section('title', 'Centroid')
@section('content')
<div style="background:#fff; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,0.07); padding:24px; margin:0 auto;">
    <h1 style="font-size:2rem; color:#888fa6; font-weight:400; margin-bottom:18px;">Centroid</h1>

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

    <div class="alert alert-info">
        <h5><i class="icon fas fa-info"></i> Tahapan K-Means Clustering</h5>
        <ol>
            <li>Menentukan jumlah cluster (K).</li>
            <li>Memilih nilai untuk pusat cluster awal (centroid) sebanyak K.</li>
            <li>Menghitung jarak setiap data ke masing-masing centroid menggunakan rumus Euclidean Distance.</li>
            <li>Menentukan cluster untuk setiap data berdasarkan jarak terdekat.</li>
            <li>Melakukan iterasi dan menentukan posisi centroid baru hingga konvergen.</li>
        </ol>
    </div>

    <h2 style="font-size:1.5rem; color:#888fa6; font-weight:400; margin:32px 0 18px;">Centroid Awal</h2>
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f4f6fa; color:#888fa6;">
                    <th style="padding:12px; text-align:left;">Data ke-</th>
                    <th style="padding:12px; text-align:left;">Cluster</th>
                    <th style="padding:12px; text-align:left;">Usia</th>
                    <th style="padding:12px; text-align:left;">Jumlah Tanggungan</th>
                    <th style="padding:12px; text-align:left;">Kondisi Rumah</th>
                    <th style="padding:12px; text-align:left;">Status Kepemilikan</th>
                    <th style="padding:12px; text-align:left;">Jumlah Penghasilan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($centroids as $i => $c)
                <tr style="border-bottom:1px solid #e5e7eb;">
                    <td style="padding:12px;">{{ $i+1 }}</td>
                    <td style="padding:12px;">C{{ $c->cluster }}</td>
                    <td style="padding:12px;">{{ $c->usia }}</td>
                    <td style="padding:12px;">{{ $c->tanggungan }}</td>
                    <td style="padding:12px;">{{ $c->kondisi_rumah }}</td>
                    <td style="padding:12px;">{{ $c->status_kepemilikan }}</td>
                    <td style="padding:12px;">{{ $c->penghasilan }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <h2 style="font-size:1.5rem; color:#888fa6; font-weight:400; margin:32px 0 18px;">Hasil Pengelompokan Data</h2>
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f4f6fa; color:#888fa6;">
                    <th style="padding:12px; text-align:left;">Data ke-</th>
                    <th style="padding:12px; text-align:left;">Jarak centroid 1</th>
                    <th style="padding:12px; text-align:left;">Jarak centroid 2</th>
                    <th style="padding:12px; text-align:left;">Jarak centroid 3</th>
                    <th style="padding:12px; text-align:left;">Penentuan Cluster</th>
                </tr>
            </thead>
            <tbody>
                @foreach($hasil as $i => $row)
                <tr style="border-bottom:1px solid #e5e7eb;">
                    <td style="padding:12px;">{{ $i+1 }}</td>
                    <td style="padding:12px;">{{ $row['jarak1'] }}</td>
                    <td style="padding:12px;">{{ $row['jarak2'] }}</td>
                    <td style="padding:12px;">{{ $row['jarak3'] }}</td>
                    <td style="padding:12px;">C{{ $row['cluster'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection 