@extends('layouts.kepala_desa')

@section('title', 'Dashboard - BANSOS KMEANS')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Dashboard</h3>
                </div>
                <div class="card-body">
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
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $layakBantuan }}</h3>
                                    <p>Layak Bantuan</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $tidakLayak }}</h3>
                                    <p>Tidak Layak</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $prioritasSedang }}</h3>
                                    <p>Prioritas Sedang</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Hasil K-Means Clustering</h3>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Nama</th>
                                                    <th>Usia</th>
                                                    <th>Jumlah Tanggungan</th>
                                                    <th>Kondisi Rumah</th>
                                                    <th>Status Kepemilikan</th>
                                                    <th>Penghasilan</th>
                                                    <th>Cluster</th>
                                                    <th>Kelayakan</th>
                                                    <th>Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($hasilKmeans as $hasil)
                                                <tr>
                                                    <td>{{ $hasil->nama_penduduk }}</td>
                                                    <td>{{ $hasil->usia }}</td>
                                                    <td>{{ $hasil->jumlah_tanggungan }}</td>
                                                    <td>{{ $hasil->kondisi_rumah }}</td>
                                                    <td>{{ $hasil->status_kepemilikan }}</td>
                                                    <td>{{ number_format($hasil->jumlah_penghasilan, 0, ',', '.') }}</td>
                                                    <td>{{ $hasil->cluster }}</td>
                                                    <td>{{ $hasil->kelayakan }}</td>
                                                    <td>{{ $hasil->keterangan }}</td>
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
            </div>
        </div>
    </div>
</div>
@endsection 