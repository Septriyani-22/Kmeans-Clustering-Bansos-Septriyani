@extends('layouts.admin')

@section('title', 'Dashboard - Sistem Bantuan Sosial')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Info Boxes -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalPenduduk }}</h3>
                    <p>Total Penduduk</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $layakBantuan ?? 0 }}</h3>
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
                    <h3>{{ $tidakLayak ?? 0 }}</h3>
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
                    <h3>{{ $prioritasSedang ?? 0 }}</h3>
                    <p>C3 - Prioritas Sedang</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Cluster -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Cluster</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h5><i class="fas fa-info-circle"></i> Penjelasan Cluster</h5>
                        <p>Jumlah cluster yang ditetapkan adalah 3 cluster berdasarkan hasil data penduduk:</p>
                        <ul>
                            <li><strong>C1 (Cluster Membutuhkan)</strong> - Kelompok yang sangat membutuhkan bantuan</li>
                            <li><strong>C2 (Cluster Tidak Membutuhkan)</strong> - Kelompok yang tidak membutuhkan bantuan</li>
                            <li><strong>C3 (Prioritas Sedang)</strong> - Kelompok dengan prioritas bantuan sedang</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Distribusi Cluster</h3>
                </div>
                <div class="card-body">
                    <canvas id="clusterChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Distribusi Penghasilan per Cluster</h3>
                </div>
                <div class="card-body">
                    <canvas id="incomeChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Hasil Clustering -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Hasil Clustering K-Means</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIK</th>
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
                                @forelse($hasilKmeans as $index => $hasil)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $hasil->penduduk->nik }}</td>
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
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center">Belum ada data hasil clustering</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.addEventListener('load', function() {
        // Cluster Distribution Chart
        var ctx = document.getElementById('clusterChart');
        if (ctx) {
            var clusterChart = new Chart(ctx.getContext('2d'), {
                type: 'pie',
                data: {
                    labels: ['C1 - Membutuhkan', 'C2 - Tidak Membutuhkan', 'C3 - Prioritas Sedang'],
                    datasets: [{
                        data: [
                            {{ $layakBantuan ?? 0 }},
                            {{ $tidakLayak ?? 0 }},
                            {{ $prioritasSedang ?? 0 }}
                        ],
                        backgroundColor: ['#dc3545', '#28a745', '#ffc107']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        title: {
                            display: true,
                            text: 'Distribusi Cluster',
                            font: {
                                size: 16
                            }
                        }
                    }
                }
            });
        }

        // Income Distribution Chart
        var incomeCtx = document.getElementById('incomeChart');
        if (incomeCtx) {
            var incomeChart = new Chart(incomeCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: ['C1 - Membutuhkan', 'C2 - Tidak Membutuhkan', 'C3 - Prioritas Sedang'],
                    datasets: [{
                        label: 'Rata-rata Penghasilan',
                        data: [
                            {{ $avgIncomeC1 ?? 0 }},
                            {{ $avgIncomeC2 ?? 0 }},
                            {{ $avgIncomeC3 ?? 0 }}
                        ],
                        backgroundColor: ['#dc3545', '#28a745', '#ffc107']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Rata-rata Penghasilan per Cluster',
                            font: {
                                size: 16
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
@endsection
