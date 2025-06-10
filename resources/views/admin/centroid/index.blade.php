@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Centroid</h3>
                </div>
                <div class="card-body">
                    @if($centroids->isEmpty())
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Belum ada data centroid. Silakan lakukan proses clustering terlebih dahulu.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Nama Centroid</th>
                                        <th>Usia</th>
                                        <th>Tanggungan</th>
                                        <th>Kondisi Rumah</th>
                                        <th>Status Kepemilikan</th>
                                        <th>Penghasilan</th>
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
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if(!empty($distanceResults))
                            <h4 class="mt-4">Perhitungan Jarak Euclidean dan Penentuan Cluster</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Data Vector</th>
                                            <th>Jarak ke C1</th>
                                            <th>Jarak ke C2</th>
                                            <th>Jarak ke C3</th>
                                            <th>Cluster Terdekat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($distanceResults as $result)
                                            <tr>
                                                <td>{{ $result['penduduk']->nama }}</td>
                                                <td>
                                                    [{{ $result['penduduk']->usia }}, 
                                                    {{ $result['penduduk']->tanggungan }}, 
                                                    {{ $result['penduduk']->kondisi_rumah }}, 
                                                    {{ $result['penduduk']->status_kepemilikan }}, 
                                                    {{ $result['penduduk']->penghasilan }}]
                                                </td>
                                                @foreach($result['distances'] as $distance)
                                                    <td>{{ number_format($distance, 2) }}</td>
                                                @endforeach
                                                <td>C{{ $result['nearest_cluster'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 