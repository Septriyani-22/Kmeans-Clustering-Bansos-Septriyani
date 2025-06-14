@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Data Mapping Centroid</h3>
                    <a href="{{ route('admin.mapping-centroid.create') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus"></i> Tambah Mapping
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Penduduk</th>
                                    <th>Centroid</th>
                                    <th>Jarak Euclidean</th>
                                    <th>Cluster</th>
                                    <th>Status Kelayakan</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($mappings as $mapping)
                                <tr>
                                    <td>{{ $mapping->penduduk->nama }}</td>
                                    <td>{{ $mapping->centroid->nama_centroid }}</td>
                                    <td>{{ number_format($mapping->jarak_euclidean, 4) }}</td>
                                    <td>Cluster {{ $mapping->cluster }}</td>
                                    <td>{{ $mapping->status_kelayakan }}</td>
                                    <td>{{ $mapping->keterangan }}</td>
                                    <td>
                                        <a href="{{ route('admin.mapping-centroid.edit', $mapping) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.mapping-centroid.destroy', $mapping) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data mapping centroid</td>
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
@endsection 