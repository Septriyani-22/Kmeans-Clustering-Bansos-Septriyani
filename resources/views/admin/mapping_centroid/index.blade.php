@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Mapping Centroid</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.mapping-centroid.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Mapping
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Penduduk</th>
                                    <th>Cluster</th>
                                    <th>Usia</th>
                                    <th>Tanggungan</th>
                                    <th>Kondisi Rumah</th>
                                    <th>Status Kepemilikan</th>
                                    <th>Penghasilan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($mappings as $mapping)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $mapping->nama_penduduk }}</td>
                                        <td>{{ $mapping->cluster }}</td>
                                        <td>{{ $mapping->usia }}</td>
                                        <td>{{ $mapping->jumlah_tanggungan }}</td>
                                        <td>{{ $mapping->kondisi_rumah }}</td>
                                        <td>{{ $mapping->status_kepemilikan }}</td>
                                        <td>{{ $mapping->jumlah_penghasilan }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editModal{{ $mapping->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('admin.mapping-centroid.destroy', $mapping->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editModal{{ $mapping->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $mapping->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel{{ $mapping->id }}">Edit Mapping</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('admin.mapping-centroid.update', $mapping->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="cluster">Cluster</label>
                                                            <select name="cluster" id="cluster" class="form-control" required>
                                                                <option value="C1" {{ $mapping->cluster == 'C1' ? 'selected' : '' }}>C1 (Rendah)</option>
                                                                <option value="C2" {{ $mapping->cluster == 'C2' ? 'selected' : '' }}>C2 (Tinggi)</option>
                                                                <option value="C3" {{ $mapping->cluster == 'C3' ? 'selected' : '' }}>C3 (Menengah)</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Tidak ada data</td>
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