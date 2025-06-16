@extends('layouts.kepala_desa')

@section('title', 'Data Penduduk - BANSOS KMEANS')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Penduduk</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIK</th>
                                    <th>Nama</th>
                                    <th>Tahun</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Usia</th>
                                    <th>RT</th>
                                    <th>Tanggungan</th>
                                    <th>Kondisi Rumah</th>
                                    <th>Status Kepemilikan</th>
                                    <th>Penghasilan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($penduduk as $p)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $p->nik }}</td>
                                    <td>{{ $p->nama }}</td>
                                    <td>{{ $p->tahun }}</td>
                                    <td>{{ $p->jenis_kelamin }}</td>
                                    <td>{{ $p->usia }}</td>
                                    <td>{{ $p->rt }}</td>
                                    <td>{{ $p->tanggungan }}</td>
                                    <td>{{ $p->kondisi_rumah }}</td>
                                    <td>{{ $p->status_kepemilikan }}</td>
                                    <td>{{ number_format($p->penghasilan, 0, ',', '.') }}</td>
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