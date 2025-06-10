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
                                    <span class="info-box-text">Prioritas Sedang</span>
                                    <span class="info-box-number">{{ $prioritasSedang }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive mt-4">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hasilKmeans as $hasil)
                                    <tr>
                                        <td>{{ $hasil['no'] }}</td>
                                        <td>{{ $hasil['nama'] }}</td>
                                        <td>{{ $hasil['kelas'] }}</td>
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