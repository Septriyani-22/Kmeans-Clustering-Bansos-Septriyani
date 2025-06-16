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
                                    <h3>{{ $totalPenduduk }}</h3>
                                    <p>Total Penduduk</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $clusterCounts['C1'] }}</h3>
                                    <p>C1 - Membutuhkan</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-heart"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $clusterCounts['C2'] }}</h3>
                                    <p>C2 - Tidak Membutuhkan</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-times"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $clusterCounts['C3'] }}</h3>
                                    <p>C3 - Prioritas Sedang</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Grafik Hasil K-Means</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="clusterChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Penduduk Terbaru</h3>
                                </div>
                                <div class="card-body p-0">
                                    <ul class="products-list product-list-in-card pl-2 pr-2">
                                        @foreach($recentPenduduk as $penduduk)
                                        <li class="item">
                                            <div class="product-info">
                                                <a href="javascript:void(0)" class="product-title">
                                                    {{ $penduduk['nama'] }}
                                                    <span class="badge badge-info float-right">{{ $penduduk['nik'] }}</span>
                                                </a>
                                                <span class="product-description">
                                                    Usia: {{ $penduduk['usia'] }} | Tanggungan: {{ $penduduk['tanggungan'] }}
                                                </span>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
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
                                                    <th>NIK</th>
                                                    <th>Nama</th>
                                                    <th>Usia</th>
                                                    <th>Tanggungan</th>
                                                    <th>Kondisi Rumah</th>
                                                    <th>Status Kepemilikan</th>
                                                    <th>Penghasilan</th>
                                                    <th>Cluster</th>
                                                    <th>Kelayakan</th>
                                                    <th>Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($paginatedResults as $result)
                                                <tr>
                                                    <td>{{ $result['nik'] }}</td>
                                                    <td>{{ $result['nama'] }}</td>
                                                    <td>{{ $result['usia'] }}</td>
                                                    <td>{{ $result['tanggungan'] }}</td>
                                                    <td>{{ $result['kondisi_rumah'] }}</td>
                                                    <td>{{ $result['status_kepemilikan'] }}</td>
                                                    <td>{{ $result['penghasilan'] }}</td>
                                                    <td>{{ $result['cluster'] }}</td>
                                                    <td>{{ $result['kelayakan'] }}</td>
                                                    <td>{{ $result['keterangan'] }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-3">
                                        {{ $paginatedResults->links() }}
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('clusterChart').getContext('2d');
    var chartData = @json($chartData);
    
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: chartData.labels,
            datasets: [{
                data: chartData.data,
                backgroundColor: [
                    '#28a745', // C1 - Hijau
                    '#ffc107', // C2 - Kuning
                    '#dc3545'  // C3 - Merah
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
});
</script>
@endpush 