@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Data Centroid</h3>
                </div>
                <div class="card-body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" id="centroidTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" id="mapping-tab" data-toggle="tab" href="#mapping" role="tab" aria-controls="mapping" aria-selected="false">
                                Mapping Centroid
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="centroid-tab" data-toggle="tab" href="#centroid" role="tab" aria-controls="centroid" aria-selected="false">
                                Data Centroid
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="pengelompokan-tab" data-toggle="tab" href="#pengelompokan" role="tab" aria-controls="pengelompokan" aria-selected="true">
                                Hasil Pengelompokan Data
                            </a>
                        </li>
                    </ul>

                    <!-- Tab content -->
                    <div class="tab-content mt-3" id="centroidTabsContent">
                        <!-- Data Centroid Tab -->
                        <div class="tab-pane fade" id="centroid" role="tabpanel" aria-labelledby="centroid-tab">
                            @if($centroids->isEmpty())
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Belum ada data centroid. Silakan lakukan proses clustering terlebih dahulu.
                                </div>
                            @else
                                @if(!empty($distanceResults))
                                    <h4 class="mt-4">Perhitungan Jarak Euclidean dan Penentuan Cluster</h4>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Nama Orang</th>
                                                    <th>Usia</th>
                                                    <th>Tanggungan</th>
                                                    <th>Kondisi Rumah</th>
                                                    <th>Status Kepemilikan</th>
                                                    <th>Penghasilan</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($distanceResults as $result)
                                                <tr>
                                                    <td>{{ $result['penduduk']->nama }}</td>
                                                    <td>{{ $result['usia'] }}</td>
                                                    <td>{{ $result['tanggungan'] }}</td>
                                                    <td>{{ $result['kondisi_rumah'] }}</td>
                                                    <td>{{ $result['status_kepemilikan'] }}</td>
                                                    <td>{{ $result['penghasilan'] }}</td>
                                                    <td>
                                                        <form action="{{ route('admin.mapping-centroid.store-from-distance') }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="penduduk_id" value="{{ $result['penduduk']->id }}">
                                                            <input type="hidden" name="centroid_id" value="{{ $centroids[$result['nearest_cluster'] - 1]->id }}">
                                                            <input type="hidden" name="jarak_euclidean" value="{{ min($result['distances']) }}">
                                                            <input type="hidden" name="cluster" value="{{ $result['nearest_cluster'] }}">
                                                            <button type="submit" class="btn btn-success btn-sm" title="Tambah ke Mapping">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            @endif
                        </div>

                        <!-- Mapping Centroid Tab -->
                        <div class="tab-pane fade" id="mapping" role="tabpanel" aria-labelledby="mapping-tab">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Data Mapping Centroid</h3>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4>Data Mapping Centroid</h4>
                                        <a href="{{ route('admin.mapping-centroid.create') }}" class="btn btn-success btn-sm">
                                            <i class="fas fa-plus"></i> Tambah Mapping
                                        </a>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Data ke-</th>
                                                    <th>Cluster</th>
                                                    <th>Usia</th>
                                                    <th>Jumlah Tanggungan</th>
                                                    <th>Kondisi Rumah</th>
                                                    <th>Status Kepemilikan</th>
                                                    <th>Jumlah Penghasilan</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($mappings as $mapping)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>Cluster {{ $mapping->cluster }}</td>
                                                        <td>{{ $mapping->penduduk->usia }}</td>
                                                        <td>{{ $mapping->penduduk->tanggungan }}</td>
                                                        <td>{{ $mapping->penduduk->kondisi_rumah }}</td>
                                                        <td>{{ $mapping->penduduk->status_kepemilikan }}</td>
                                                        <td>Rp {{ number_format($mapping->penduduk->penghasilan, 0, ',', '.') }}</td>
                                                        <td>
                                                            <a href="{{ route('admin.centroid.mapping.edit', $mapping) }}" class="btn btn-sm btn-warning">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </a>
                                                            <form action="{{ route('admin.centroid.mapping.destroy', $mapping) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                                    <i class="fas fa-trash"></i> Hapus
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="8" class="text-center">Tidak ada data mapping centroid</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hasil Pengelompokan Data Tab -->
                        <div class="tab-pane fade show active" id="pengelompokan" role="tabpanel" aria-labelledby="pengelompokan-tab">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Data ke-</th>
                                            <th>Nama Penduduk</th>
                                            <th>Jarak ke Centroid 1</th>
                                            <th>Jarak ke Centroid 2</th>
                                            <th>Jarak ke Centroid 3</th>
                                            <th>Penentuan Cluster</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($distanceResults ?? [] as $index => $result)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $result['penduduk']->nama }}</td>
                                            <td>{{ number_format($result['distances'][0], 4) }}</td>
                                            <td>{{ number_format($result['distances'][1], 4) }}</td>
                                            <td>{{ number_format($result['distances'][2], 4) }}</td>
                                            <td>Cluster {{ $result['nearest_cluster'] }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Belum ada hasil pengelompokan data</td>
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
    </div>
</div>
@endsection 