@extends('layouts.admin')

@section('title', 'Hasil K-Means - BANSOS KMEANS')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Hasil K-Means Clustering</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#printWizardModal">
                            <i class="fas fa-print"></i> Cetak Clustering
                        </button>
                    </div>
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
                                    <th>Jumlah Tanggungan</th>
                                    <th>Kondisi Rumah</th>
                                    <th>Status Kepemilikan</th>
                                    <th>Jumlah Penghasilan</th>
                                    <th>Cluster</th>
                                    <th>Kelayakan</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($hasilKmeans as $result)
                                <tr>
                                        <td>{{ $loop->iteration }}</td>
                                    <td>{{ $result->nama_penduduk }}</td>
                                    <td>{{ $result->usia }}</td>
                                    <td>{{ $result->jumlah_tanggungan }}</td>
                                    <td>{{ $result->kondisi_rumah }}</td>
                                    <td>{{ $result->status_kepemilikan }}</td>
                                    <td>Rp {{ number_format($result->jumlah_penghasilan, 0, ',', '.') }}</td>
                                    <td>{{ $result->cluster }}</td>
                                        <td>
                                            <span class="badge badge-{{ $result->kelayakan == 'Layak' ? 'success' : 'danger' }}">
                                                {{ $result->kelayakan }}
                                        </span>
                                    </td>
                                        <td>{{ $result->keterangan }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">Tidak ada data hasil clustering</td>
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

<!-- Print Wizard Modal -->
<div class="modal fade" id="printWizardModal" tabindex="-1" role="dialog" aria-labelledby="printWizardModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="printWizardModalLabel">Cetak Hasil Clustering</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="printForm" action="{{ route('admin.hasil-kmeans.print') }}" method="GET" target="_blank">
                    <div class="form-group">
                        <label>Pilih Cluster yang akan dicetak:</label>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="cluster1" name="clusters[]" value="1">
                            <label class="custom-control-label" for="cluster1">Cluster 1</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="cluster2" name="clusters[]" value="2">
                            <label class="custom-control-label" for="cluster2">Cluster 2</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="cluster3" name="clusters[]" value="3">
                            <label class="custom-control-label" for="cluster3">Cluster 3</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Format Output:</label>
                        <select class="form-control" name="format">
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('printForm').submit()">Cetak</button>
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

@push('scripts')
<script>
$(document).ready(function() {
    // Ensure at least one cluster is selected
    $('input[name="clusters[]"]').change(function() {
        if($('input[name="clusters[]"]:checked').length === 0) {
            $(this).prop('checked', true);
        }
    });
});
</script>
@endpush 