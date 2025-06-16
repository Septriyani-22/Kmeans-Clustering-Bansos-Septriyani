@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.kepala_desa')

@section('title', 'Hasil K-Means - BANSOS KMEANS')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Hasil K-Means Clustering</h3>
                    @if(auth()->user()->role === 'admin')
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#prosesModal">
                            <i class="fas fa-calculator"></i> Proses K-Means
                        </button>
                        <a href="{{ route('admin.clustering.reset') }}" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin mereset semua data perhitungan?')">
                            <i class="fas fa-trash"></i> Reset
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if(auth()->user()->role === 'admin')
<!-- Modal Proses K-Means -->
<div class="modal fade" id="prosesModal" tabindex="-1" role="dialog" aria-labelledby="prosesModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="prosesModalLabel">Proses K-Means Clustering</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.clustering.proses') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="jumlah_cluster">Jumlah Cluster</label>
                        <input type="number" class="form-control" id="jumlah_cluster" name="jumlah_cluster" value="3" min="2" max="10" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Proses</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('clusterChart').getContext('2d');
    var chartData = {
        labels: ['C1 (Membutuhkan)', 'C2 (Tidak Membutuhkan)', 'C3 (Prioritas Sedang)'],
        data: [
            {{ $clusterCounts['C1'] ?? 0 }},
            {{ $clusterCounts['C2'] ?? 0 }},
            {{ $clusterCounts['C3'] ?? 0 }}
        ]
    };
    
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