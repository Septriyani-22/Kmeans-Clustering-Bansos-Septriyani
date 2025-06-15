@extends('welcome')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Hasil Pencarian</div>

                <div class="card-body">
                    @if(isset($data))
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="200">NIK</th>
                                    <td>{{ $data['nik'] }}</td>
                                </tr>
                                <tr>
                                    <th>Nama</th>
                                    <td>{{ $data['nama'] }}</td>
                                </tr>
                                <tr>
                                    <th>Usia</th>
                                    <td>{{ $data['usia'] }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggungan</th>
                                    <td>{{ $data['tanggungan'] }}</td>
                                </tr>
                                <tr>
                                    <th>Kondisi Rumah</th>
                                    <td>{{ $data['kondisi_rumah'] }}</td>
                                </tr>
                                <tr>
                                    <th>Status Kepemilikan</th>
                                    <td>{{ $data['status_kepemilikan'] }}</td>
                                </tr>
                                <tr>
                                    <th>Penghasilan</th>
                                    <td>{{ $data['penghasilan'] }}</td>
                                </tr>
                                <tr>
                                    <th>Cluster</th>
                                    <td>
                                        @if($data['cluster'] == 'C1')
                                            <span class="badge badge-danger">{{ $data['cluster'] }}</span>
                                        @elseif($data['cluster'] == 'C2')
                                            <span class="badge badge-success">{{ $data['cluster'] }}</span>
                                        @else
                                            <span class="badge badge-warning">{{ $data['cluster'] }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Kelayakan</th>
                                    <td>{{ $data['kelayakan'] }}</td>
                                </tr>
                                <tr>
                                    <th>Keterangan</th>
                                    <td>{{ $data['keterangan'] }}</td>
                                </tr>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-danger">
                            Data tidak ditemukan
                        </div>
                    @endif

                    <div class="mt-3">
                        <a href="{{ url('/') }}" class="btn btn-primary">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 