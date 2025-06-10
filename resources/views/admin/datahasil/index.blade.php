@extends('layouts.admin')

@section('title', 'Data Hasil Clustering')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Data Hasil Clustering</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>NIK</th>
                                    <th>Nama</th>
                                    <th>Penghasilan</th>
                                    <th>Tanggungan</th>
                                    <th>Cluster</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hasil as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->penduduk->nik }}</td>
                                    <td>{{ $item->penduduk->nama }}</td>
                                    <td>{{ $item->penduduk->penghasilan }}</td>
                                    <td>{{ $item->penduduk->tanggungan }}</td>
                                    <td>{{ $item->cluster }}</td>
                                    <td>
                                        <span class="badge {{ $item->hasil == 'Layak' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $item->hasil }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.datahasil.export') }}" class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Export Data
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.table-hover tbody tr:hover {
    background-color: rgba(0,0,0,.075);
}

.thead-light th {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}

.badge {
    display: inline-block;
    padding: 0.35em 0.65em;
    font-size: 0.75em;
    font-weight: 700;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0.25rem;
}

.bg-success {
    background-color: #28a745 !important;
}

.bg-danger {
    background-color: #dc3545 !important;
}
</style>
@endsection 