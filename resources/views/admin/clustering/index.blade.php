@extends('layouts.admin')

@section('title', 'Clustering - BANSOS KMEANS')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Clustering K-Means</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <h5><i class="icon fas fa-info"></i> Informasi Cluster</h5>
                                <p>Jumlah cluster yang ditetapkan adalah 3 cluster berdasarkan hasil data penduduk:</p>
                                <ul>
                                    <li><strong>C1 (Cluster Membutuhkan)</strong> - Kelompok yang sangat membutuhkan bantuan</li>
                                    <li><strong>C2 (Cluster Tidak Membutuhkan)</strong> - Kelompok yang tidak membutuhkan bantuan</li>
                                    <li><strong>C3 (Prioritas Sedang)</strong> - Kelompok dengan prioritas bantuan sedang</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.clustering.proses') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="jumlah_cluster">Jumlah Cluster</label>
                            <input type="number" class="form-control @error('jumlah_cluster') is-invalid @enderror" 
                                id="jumlah_cluster" name="jumlah_cluster" value="3" min="2" max="10" required readonly>
                            @error('jumlah_cluster')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Jumlah cluster telah ditetapkan 3 berdasarkan analisis data penduduk.</small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-play"></i> Mulai Proses Clustering
                            </button>
                            <a href="{{ route('admin.clustering.reset') }}" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin mereset semua data clustering?')">
                                <i class="fas fa-trash"></i> Reset Data
                            </a>
                        </div>
                    </form>

                    @if(isset($hasil_clustering) && $hasil_clustering->isNotEmpty())
                    <div class="mt-4">
                        <h4>Hasil Clustering</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>Cluster</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($hasil_clustering as $hasil)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $hasil->penduduk->nik }}</td>
                                            <td>{{ $hasil->penduduk->nama }}</td>
                                            <td>
                                                <span class="badge badge-{{ $hasil->cluster_badge }}">
                                                    C{{ $hasil->cluster }} ({{ $hasil->cluster_name }})
                                                </span>
                                            </td>
                                            <td>{{ $hasil->cluster_description }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 