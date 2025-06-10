@extends('layouts.admin')

@section('title', 'Dashboard - Sistem Bantuan Sosial')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Info boxes -->
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Penduduk</span>
                    <span class="info-box-number">{{ $totalPenduduk }}</span>
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
                    <h3 class="card-title">Distribusi Penghasilan</h3>
                </div>
                <div class="card-body">
                    <canvas id="incomeChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Hasil Clustering Terbaru</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>NIK</th>
                                    <th>Nama</th>
                                    <th>Cluster</th>
                                    <th>Skor Kelayakan</th>
                                    <th>Status</th>
                                    <th>Penghasilan</th>
                                    <th>Tanggungan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestResults as $result)
                                    <tr>
                                        <td>{{ $result->penduduk->nik }}</td>
                                        <td>{{ $result->penduduk->nama }}</td>
                                        <td>
                                            <span class="badge badge-info">{{ $result->cluster }}</span>
                                        </td>
                                        <td>{{ number_format($result->skor_kelayakan, 2) }}</td>
                                        <td>
                                            <span class="badge {{ $result->kelayakan === 'Layak' ? 'badge-success' : 'badge-danger' }}">
                                                {{ $result->kelayakan }}
                                            </span>
                                        </td>
                                        <td>{{ $result->penduduk->penghasilan }}</td>
                                        <td>{{ $result->penduduk->tanggungan }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Belum ada data hasil clustering</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Top 5 Skor Tertinggi</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>NIK</th>
                                    <th>Nama</th>
                                    <th>Skor Kelayakan</th>
                                    <th>Penghasilan</th>
                                    <th>Tanggungan</th>
                                    <th>Kondisi Rumah</th>
                                    <th>Status Kepemilikan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topScores as $score)
                                    <tr>
                                        <td>{{ $score->penduduk->nik }}</td>
                                        <td>{{ $score->penduduk->nama }}</td>
                                        <td>{{ number_format($score->skor_kelayakan, 2) }}</td>
                                        <td>{{ $score->penduduk->penghasilan }}</td>
                                        <td>{{ $score->penduduk->tanggungan }}</td>
                                        <td>{{ $score->penduduk->kondisi_rumah }}</td>
                                        <td>{{ $score->penduduk->status_kepemilikan_rumah }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Belum ada data hasil clustering</td>
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
    // Tunggu sampai DOM dan Chart.js siap
    window.addEventListener('load', function() {
        // Debug data
        console.log('Cluster Labels:', {!! json_encode($clusterLabels) !!});
        console.log('Cluster Values:', {!! json_encode($clusterValues) !!});
        console.log('Income Labels:', {!! json_encode($incomeLabels) !!});
        console.log('Income Values:', {!! json_encode($incomeValues) !!});

        // Cluster Distribution Chart
        var ctx = document.getElementById('clusterChart');
        if (ctx) {
            var clusterChart = new Chart(ctx.getContext('2d'), {
                type: 'pie',
                data: {
                    labels: {!! json_encode($clusterLabels) !!},
                    datasets: [{
                        data: {!! json_encode($clusterValues) !!},
                        backgroundColor: [
                            '#3b82f6', '#f59e42', '#10b981', '#ef4444', '#6366f1', '#fbbf24', '#6ee7b7', '#f472b6'
                        ],
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { 
                            position: 'bottom',
                            labels: {
                                font: {
                                    size: 12
                                }
                            }
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
                    labels: {!! json_encode($incomeLabels) !!},
                    datasets: [{
                        label: 'Jumlah Penduduk',
                        data: {!! json_encode($incomeValues) !!},
                        backgroundColor: ['#dc3545', '#28a745', '#17a2b8']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Distribusi Penghasilan',
                            font: {
                                size: 16
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
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
