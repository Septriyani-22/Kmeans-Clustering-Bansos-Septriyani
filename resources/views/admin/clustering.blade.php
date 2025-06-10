@extends('layouts.admin')

@section('title', 'Clustering - BANSOS KMEANS')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="header-section" style="background:#2563eb; padding:2rem; border-radius:12px; margin-bottom:2rem; color:white;">
        <h1 style="font-size:2rem; margin:0;">Proses Clustering</h1>
        <p style="margin:0.5rem 0 0; opacity:0.9;">K-Means Clustering untuk Analisis Kelayakan Bantuan Sosial</p>
    </div>

    <!-- Main Content -->
    <div class="row">
        <!-- Left Column -->
        <div class="col-md-8">
            <!-- Centroid Data -->
            <div class="card" style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,0.07); margin-bottom:2rem;">
                <h2 style="color:#64748b; font-size:1.25rem; margin-bottom:1.5rem;">Data Centroid</h2>
                <div style="overflow-x:auto;">
                    <table style="width:100%; border-collapse:collapse;">
                        <thead>
                            <tr style="background:#f8fafc;">
                                <th style="padding:12px; text-align:left; color:#64748b; font-weight:500;">Nama Centroid</th>
                                <th style="padding:12px; text-align:left; color:#64748b; font-weight:500;">Penghasilan</th>
                                <th style="padding:12px; text-align:left; color:#64748b; font-weight:500;">Tanggungan</th>
                                <th style="padding:12px; text-align:left; color:#64748b; font-weight:500;">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($centroids as $centroid)
                            <tr style="border-bottom:1px solid #e2e8f0;">
                                <td style="padding:12px;">{{ $centroid->nama_centroid }}</td>
                                <td style="padding:12px;">Rp {{ number_format($centroid->penghasilan, 0, ',', '.') }}</td>
                                <td style="padding:12px;">{{ $centroid->tanggungan }} orang</td>
                                <td style="padding:12px;">{{ $centroid->keterangan }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Normalization Data -->
            <div class="card" style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,0.07);">
                <h2 style="color:#64748b; font-size:1.25rem; margin-bottom:1.5rem;">Data Normalisasi</h2>
                <div style="overflow-x:auto;">
                    <table style="width:100%; border-collapse:collapse;">
                        <thead>
                            <tr style="background:#f8fafc;">
                                <th style="padding:12px; text-align:left; color:#64748b; font-weight:500;">NIK</th>
                                <th style="padding:12px; text-align:left; color:#64748b; font-weight:500;">Nama</th>
                                <th style="padding:12px; text-align:left; color:#64748b; font-weight:500;">Penghasilan (Normal)</th>
                                <th style="padding:12px; text-align:left; color:#64748b; font-weight:500;">Tanggungan (Normal)</th>
                                <th style="padding:12px; text-align:left; color:#64748b; font-weight:500;">Cluster</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($normalizedData as $data)
                            <tr style="border-bottom:1px solid #e2e8f0;">
                                <td style="padding:12px;">{{ $data->nik }}</td>
                                <td style="padding:12px;">{{ $data->nama }}</td>
                                <td style="padding:12px;">{{ number_format($data->penghasilan_normal, 2) }}</td>
                                <td style="padding:12px;">{{ number_format($data->tanggungan_normal, 2) }}</td>
                                <td style="padding:12px;">{{ $data->cluster }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-4">
            <!-- Action Buttons -->
            <div class="card" style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,0.07); margin-bottom:2rem;">
                <h2 style="color:#64748b; font-size:1.25rem; margin-bottom:1.5rem;">Aksi</h2>
                <div style="display:grid; gap:1rem;">
                    <form action="{{ route('admin.clustering.proses') }}" method="POST">
                        @csrf
                        <button type="submit" style="width:100%; background:#2563eb; color:white; padding:1rem; border:none; border-radius:8px; cursor:pointer;">
                            <i class="fas fa-cogs"></i> Proses Clustering
                        </button>
                    </form>
                    <a href="{{ route('admin.clustering.reset') }}" style="background:#dc2626; color:white; padding:1rem; border-radius:8px; text-decoration:none; text-align:center;">
                        <i class="fas fa-trash"></i> Reset Data
                    </a>
                    <a href="{{ route('admin.datahasil.export') }}" style="background:#16a34a; color:white; padding:1rem; border-radius:8px; text-decoration:none; text-align:center;">
                        <i class="fas fa-file-excel"></i> Export Data
                    </a>
                </div>
            </div>

            <!-- Clustering Info -->
            <div class="card" style="background:white; padding:1.5rem; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,0.07);">
                <h2 style="color:#64748b; font-size:1.25rem; margin-bottom:1.5rem;">Informasi Clustering</h2>
                <div style="color:#64748b;">
                    <p style="margin-bottom:1rem;">Proses clustering menggunakan algoritma K-Means dengan 3 centroid:</p>
                    <ul style="list-style:none; padding:0; margin:0;">
                        <li style="margin-bottom:0.5rem; padding-left:1.5rem; position:relative;">
                            <span style="position:absolute; left:0; color:#2563eb;">•</span>
                            Cluster 1: Kelompok dengan penghasilan rendah dan tanggungan banyak
                        </li>
                        <li style="margin-bottom:0.5rem; padding-left:1.5rem; position:relative;">
                            <span style="position:absolute; left:0; color:#2563eb;">•</span>
                            Cluster 2: Kelompok dengan penghasilan menengah dan tanggungan sedang
                        </li>
                        <li style="margin-bottom:0.5rem; padding-left:1.5rem; position:relative;">
                            <span style="position:absolute; left:0; color:#2563eb;">•</span>
                            Cluster 3: Kelompok dengan penghasilan tinggi dan tanggungan sedikit
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 