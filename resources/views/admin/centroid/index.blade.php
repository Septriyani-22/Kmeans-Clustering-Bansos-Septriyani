@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Centroid Management</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <a href="{{ route('admin.centroid.create') }}" class="btn btn-primary">Add New Centroid</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Centroid</th>
                                    <th>Usia</th>
                                    <th>Jumlah Tanggungan</th>
                                    <th>Kondisi Rumah</th>
                                    <th>Status Kepemilikan</th>
                                    <th>Jumlah Penghasilan</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($centroids as $centroid)
                                <tr>
                                    <td>{{ $centroid->nama_centroid }}</td>
                                    <td>{{ $centroid->usia }}</td>
                                    <td>{{ $centroid->tanggungan_num }}</td>
                                    <td>{{ $centroid->kondisi_rumah }}</td>
                                    <td>{{ $centroid->status_kepemilikan }}</td>
                                    <td>Rp {{ number_format($centroid->penghasilan_num, 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ route('admin.centroid.edit', $centroid->id) }}" class="btn btn-sm btn-info">Edit</a>
                                        <form action="{{ route('admin.centroid.destroy', $centroid->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this centroid?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Perhitungan Jarak Euclidean dan Penentuan Cluster</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    @foreach($centroids as $centroid)
                                        <th>Jarak ke {{ $centroid->nama_centroid }}</th>
                                    @endforeach
                                    <th>Cluster Terdekat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($distanceResults as $result)
                                <tr>
                                    <td>{{ $result['penduduk']->nama }}</td>
                                    @foreach($result['distances'] as $distance)
                                        <td>{{ number_format($distance, 6) }}</td>
                                    @endforeach
                                    <td>C{{ $result['cluster'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 