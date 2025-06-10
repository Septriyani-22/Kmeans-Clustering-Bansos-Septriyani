@extends('layouts.app')

@section('title', 'Dashboard - Sistem Bantuan Sosial')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Penduduk</h3>
                </div>
                <div class="card-body">
                    @if($penduduk)
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="200">NIK</th>
                                        <td>{{ $penduduk->nik }}</td>
                                    </tr>
                                    <tr>
                                        <th>Nama</th>
                                        <td>{{ $penduduk->nama }}</td>
                                    </tr>
                                    <tr>
                                        <th>Usia</th>
                                        <td>{{ $penduduk->usia }} tahun</td>
                                    </tr>
                                    <tr>
                                        <th>Tanggungan</th>
                                        <td>{{ $penduduk->tanggungan }}</td>
                                    </tr>
                                    <tr>
                                        <th>Kondisi Rumah</th>
                                        <td>{{ $penduduk->kondisi_rumah }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status Kepemilikan</th>
                                        <td>{{ $penduduk->status_kepemilikan }}</td>
                                    </tr>
                                    <tr>
                                        <th>Penghasilan</th>
                                        <td>Rp {{ number_format($penduduk->penghasilan, 0, ',', '.') }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                @if($hasilKmeans)
                                    <div class="alert alert-info">
                                        <h5><i class="fas fa-info-circle"></i> Hasil Clustering</h5>
                                        <p>Berdasarkan data yang ada, Anda termasuk dalam:</p>
                                        @php
                                            $clusterName = match($hasilKmeans->cluster) {
                                                1 => 'Membutuhkan',
                                                2 => 'Tidak Membutuhkan',
                                                3 => 'Prioritas Sedang',
                                                default => 'Tidak Diketahui'
                                            };
                                            $badgeClass = match($hasilKmeans->cluster) {
                                                1 => 'danger',
                                                2 => 'success',
                                                3 => 'warning',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <h4>
                                            <span class="badge bg-{{ $badgeClass }}">
                                                C{{ $hasilKmeans->cluster }} - {{ $clusterName }}
                                            </span>
                                        </h4>
                                        <p class="mt-3">
                                            <strong>Jarak ke Centroid:</strong> {{ number_format($hasilKmeans->jarak, 2) }}
                                        </p>
                                    </div>
                                @else
                                    <div class="alert alert-warning">
                                        <h5><i class="fas fa-exclamation-triangle"></i> Belum Ada Hasil</h5>
                                        <p>Data Anda belum diproses dalam clustering. Silakan tunggu hingga proses clustering selesai.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="alert alert-danger">
                            <h5><i class="fas fa-exclamation-circle"></i> Data Tidak Ditemukan</h5>
                            <p>Data penduduk tidak ditemukan. Silakan hubungi administrator.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 