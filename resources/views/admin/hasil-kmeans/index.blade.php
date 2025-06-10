@extends('layouts.admin')

@section('title', 'Hasil K-Means - BANSOS KMEANS')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Hasil Clustering K-Means</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h5><i class="fas fa-info-circle"></i> Informasi Cluster</h5>
                        <p>Jumlah cluster yang ditetapkan adalah 3 cluster berdasarkan hasil data penduduk:</p>
                        <ul>
                            <li><strong>C1 (Cluster Membutuhkan)</strong> - Kelompok yang sangat membutuhkan bantuan</li>
                            <li><strong>C2 (Cluster Tidak Membutuhkan)</strong> - Kelompok yang tidak membutuhkan bantuan</li>
                            <li><strong>C3 (Prioritas Sedang)</strong> - Kelompok dengan prioritas bantuan sedang</li>
                        </ul>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $totalData }}</h3>
                                    <p>Total Data</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $layakBantuan }}</h3>
                                    <p>C1 - Membutuhkan</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-heart"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $tidakLayak }}</h3>
                                    <p>C2 - Tidak Membutuhkan</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $prioritasSedang }}</h3>
                                    <p>C3 - Prioritas Sedang</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Usia</th>
                                    <th>Tanggungan</th>
                                    <th>Kondisi Rumah</th>
                                    <th>Status Kepemilikan</th>
                                    <th>Penghasilan</th>
                                    <th>Cluster</th>
                                    <th>Jarak</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hasilKmeans as $index => $hasil)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $hasil->penduduk->nama }}</td>
                                    <td>{{ $hasil->penduduk->usia }}</td>
                                    <td>{{ $hasil->penduduk->tanggungan }}</td>
                                    <td>{{ $hasil->penduduk->kondisi_rumah }}</td>
                                    <td>{{ $hasil->penduduk->status_kepemilikan }}</td>
                                    <td>Rp {{ number_format($hasil->penduduk->penghasilan, 0, ',', '.') }}</td>
                                    <td>
                                        @php
                                            $clusterName = match($hasil->cluster) {
                                                1 => 'Membutuhkan',
                                                2 => 'Tidak Membutuhkan',
                                                3 => 'Prioritas Sedang',
                                                default => 'Tidak Diketahui'
                                            };
                                            $badgeClass = match($hasil->cluster) {
                                                1 => 'danger',
                                                2 => 'success',
                                                3 => 'warning',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $badgeClass }}">
                                            C{{ $hasil->cluster }} - {{ $clusterName }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($hasil->jarak, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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