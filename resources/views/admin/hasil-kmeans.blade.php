@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Hasil K-Means Clustering</h3>
                </div>
                <div class="card-body">
                    <!-- Centroid Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4>Centroid Awal</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Data ke-</th>
                                            <th>Cluster</th>
                                            <th>Usia</th>
                                            <th>Jumlah Tanggungan</th>
                                            <th>Kondisi Rumah</th>
                                            <th>Status Kepemilikan</th>
                                            <th>Jumlah Penghasilan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($centroids as $centroid)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>C{{ $loop->iteration }}</td>
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
                        </div>
                    </div>

                    <!-- Cluster Statistics -->
                    <div class="row mb-4">
                        @foreach($clusterStats as $cluster => $stats)
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header bg-{{ $cluster == 1 ? 'success' : ($cluster == 2 ? 'warning' : 'danger') }}">
                                        <h5 class="card-title text-white">Cluster {{ $cluster }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Jumlah Data:</strong> {{ $stats['count'] }}</p>
                                        <p><strong>Rata-rata Usia:</strong> {{ number_format($stats['avg_usia'], 1) }} tahun</p>
                                        <p><strong>Rata-rata Tanggungan:</strong> {{ number_format($stats['avg_tanggungan'], 1) }} orang</p>
                                        <p><strong>Rata-rata Penghasilan:</strong> Rp {{ number_format($stats['avg_penghasilan'], 0, ',', '.') }}</p>
                                        <hr>
                                        <p><strong>Kondisi Rumah:</strong></p>
                                        <ul>
                                            <li>Baik: {{ $stats['baik'] }}</li>
                                            <li>Cukup: {{ $stats['cukup'] }}</li>
                                            <li>Kurang: {{ $stats['kurang'] }}</li>
                                        </ul>
                                        <p><strong>Status Kepemilikan:</strong></p>
                                        <ul>
                                            <li>Hak Milik: {{ $stats['hak_milik'] }}</li>
                                            <li>Numpang: {{ $stats['numpang'] }}</li>
                                            <li>Sewa: {{ $stats['sewa'] }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Detailed Results -->
                    <div class="row">
                        <div class="col-12">
                            <h4>Detail Hasil Clustering</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>NIK</th>
                                            <th>Nama</th>
                                            <th>Usia</th>
                                            <th>Tanggungan</th>
                                            <th>Kondisi Rumah</th>
                                            <th>Status Kepemilikan</th>
                                            <th>Penghasilan</th>
                                            <th>Cluster</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($penduduk as $p)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $p->nik }}</td>
                                                <td>{{ $p->nama }}</td>
                                                <td>{{ $p->usia }}</td>
                                                <td>{{ $p->tanggungan }}</td>
                                                <td>{{ $p->kondisi_rumah }}</td>
                                                <td>{{ $p->status_kepemilikan }}</td>
                                                <td>Rp {{ number_format($p->penghasilan, 0, ',', '.') }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $p->cluster == 1 ? 'success' : ($p->cluster == 2 ? 'warning' : 'danger') }}">
                                                        Cluster {{ $p->cluster }}
                                                    </span>
                                                </td>
                                            </tr>
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
    </div>
</div>
@endsection 