@extends('layouts.kepala_desa')

@section('title', 'Dashboard - SibansosTanser')

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
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Grafik Hasil K-Means</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="clusterChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
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
                                                    <th>Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($paginatedResults as $result)
                                                    @if($result->penduduk)
                                                        <tr>
                                                            <td>{{ $result->penduduk->nik }}</td>
                                                            <td>{{ $result->penduduk->nama }}</td>
                                                            <td>{{ $result->penduduk->usia }}</td>
                                                            <td>{{ $result->penduduk->tanggungan }}</td>
                                                            <td>{{ ucfirst($result->penduduk->kondisi_rumah) }}</td>
                                                            <td>{{ ucfirst(str_replace('_', ' ', $result->penduduk->status_kepemilikan)) }}</td>
                                                            <td>Rp {{ number_format($result->penduduk->penghasilan, 0, ',', '.') }}</td>
                                                            <td>C{{ $result->centroid_id }}</td>
                                                            <td>
                                                                @if($result->centroid_id == 1)
                                                                    Membutuhkan
                                                                @elseif($result->centroid_id == 2)
                                                                    Tidak Membutuhkan
                                                                @else
                                                                    Prioritas sedang
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @empty
                                                    <tr>
                                                        <td colspan="10" class="text-center">Tidak ada data hasil clustering</td>
                                                    </tr>
                                                @endforelse
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