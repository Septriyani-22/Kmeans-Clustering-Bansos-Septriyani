@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Total Penduduk Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Penduduk</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPenduduk }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- C1 Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                C1 (Membutuhkan)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $clusterCounts['C1'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- C2 Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                C2 (Tidak Membutuhkan)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $clusterCounts['C2'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- C3 Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                C3 (Prioritas Sedang)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $clusterCounts['C3'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Pie Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Distribusi Cluster</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4">
                        <canvas id="clusterChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Penduduk -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Penduduk Terbaru</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>NIK</th>
                                    <th>Nama</th>
                                    <th>Usia</th>
                                    <th>Tanggungan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPenduduk as $penduduk)
                                <tr>
                                    <td>{{ $penduduk['nik'] }}</td>
                                    <td>{{ $penduduk['nama'] }}</td>
                                    <td>{{ $penduduk['usia'] }}</td>
                                    <td>{{ $penduduk['tanggungan'] }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada data penduduk</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Hasil K-Means Table -->
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Hasil K-Means Clustering</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>NIK</th>
                                    <th>Nama</th>
                                    <th>Usia</th>
                                    <th>Tanggungan</th>
                                    <th>Kondisi Rumah</th>
                                    <th>Status Kepemilikan</th>
                                    <th>Penghasilan</th>
                                    <th>Cluster</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($paginatedResults as $result)
                                <tr>
                                    <td>{{ $result['nik'] }}</td>
                                    <td>{{ $result['nama'] }}</td>
                                    <td>{{ $result['usia'] }}</td>
                                    <td>{{ $result['tanggungan'] }}</td>
                                    <td>{{ $result['kondisi_rumah'] }}</td>
                                    <td>{{ $result['status_kepemilikan'] }}</td>
                                    <td>{{ $result['penghasilan'] }}</td>
                                    <td>{{ $result['kelayakan'] }}</td>
                                    <td>{{ $result['keterangan'] }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center">Tidak ada data hasil clustering</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        @if($paginatedResults->hasPages())
                            <div class="pagination">
                                @foreach($paginatedResults->getUrlRange(1, $paginatedResults->lastPage()) as $page => $url)
                                    <a href="{{ $url }}" class="btn btn-sm {{ $page == $paginatedResults->currentPage() ? 'btn-primary' : 'btn-outline-primary' }} mr-1">
                                        {{ $page }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Pie Chart
    var ctx = document.getElementById('clusterChart').getContext('2d');
    var myPieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: [{
                data: {!! json_encode($chartData['data']) !!},
                backgroundColor: ['#e74a3b', '#1cc88a', '#36b9cc'],
                hoverBackgroundColor: ['#d63c2e', '#17a673', '#2c9faf'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
            },
            legend: {
                display: true,
                position: 'bottom'
            },
            cutoutPercentage: 0,
        },
    });
});
</script>
@endpush
@endsection 