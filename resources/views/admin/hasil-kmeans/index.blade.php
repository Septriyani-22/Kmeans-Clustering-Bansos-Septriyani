@extends('layouts.admin')

@section('title', 'Hasil K-Means - BANSOS KMEANS')

@section('content')
<div class="container-fluid">
    <!-- Info boxes -->
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Data</span>
                    <span class="info-box-number">{{ $totalData }}</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Layak Bantuan</span>
                    <span class="info-box-number">{{ $layakBantuan }}</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-times-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Tidak Layak</span>
                    <span class="info-box-number">{{ $tidakLayak }}</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-chart-pie"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Rata-rata Skor</span>
                    <span class="info-box-number">{{ number_format($avgScore, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Hasil Clustering K-Means</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.hasil-kmeans.export') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>NIK</th>
                                    <th>Nama</th>
                                    <th>Cluster</th>
                                    <th>Skor Kelayakan</th>
                                    <th>Kelayakan</th>
                                    <th>Skor Penghasilan</th>
                                    <th>Skor Tanggungan</th>
                                    <th>Skor Kondisi Rumah</th>
                                    <th>Skor Status Kepemilikan</th>
                                    <th>Skor Usia</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($hasilKmeans as $hasil)
                                    <tr>
                                        <td>{{ $hasil->penduduk->nik }}</td>
                                        <td>{{ $hasil->penduduk->nama }}</td>
                                        <td>{{ $hasil->centroid->nama_centroid }}</td>
                                        <td>{{ number_format($hasil->skor_kelayakan, 2) }}</td>
                                        <td>
                                            <span class="badge {{ $hasil->kelayakan === 'Layak' ? 'badge-success' : 'badge-danger' }}" style="color: white;">
                                                {{ $hasil->kelayakan }}
                                            </span>
                                        </td>
                                        <td>{{ number_format($hasil->skor_penghasilan, 2) }}</td>
                                        <td>{{ number_format($hasil->skor_tanggungan, 2) }}</td>
                                        <td>{{ number_format($hasil->skor_kondisi_rumah, 2) }}</td>
                                        <td>{{ number_format($hasil->skor_status_kepemilikan, 2) }}</td>
                                        <td>{{ number_format($hasil->skor_usia, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">Belum ada data hasil clustering</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer clearfix">
                    <div class="d-flex justify-content-center">
                        {{ $hasilKmeans->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .pagination {
        margin: 0;
    }
    .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
    }
    .page-link {
        color: #007bff;
    }
    .page-link:hover {
        color: #0056b3;
    }
</style>
@endpush
@endsection 