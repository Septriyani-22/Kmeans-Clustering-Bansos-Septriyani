@extends('layouts.admin')

@section('title', 'Centroid - BANSOS KMEANS')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Centroid</h3>
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

                    @if(session('warning'))
                        <div class="alert alert-warning">
                            {{ session('warning') }}
                        </div>
                    @endif

                    <!-- Tab Navigation -->
                    <ul class="nav nav-tabs" id="centroidTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="data-tab" data-toggle="tab" href="#data" role="tab">
                                Data Centroid
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="mapping-tab" data-toggle="tab" href="#mapping" role="tab">
                                Mapping Centroid
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="clustering-tab" data-toggle="tab" href="#clustering" role="tab">
                                Clustering
                            </a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content mt-3" id="centroidTabContent">
                        <!-- Data Centroid Tab -->
                        <div class="tab-pane fade show active" id="data" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Data ke-</th>
                                            <th>Nama Penduduk</th>
                                            <th>Usia</th>
                                            <th>Jumlah Tanggungan</th>
                                            <th>Kondisi Rumah</th>
                                            <th>Status Kepemilikan</th>
                                            <th>Jumlah Penghasilan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($convertedPenduduks as $penduduk)
                                        <tr>
                                            <td>{{ $penduduk['id'] }}</td>
                                            <td>{{ $penduduk['nama'] }}</td>
                                            <td>{{ $penduduk['usia'] }}</td>
                                            <td>{{ $penduduk['jumlah_tanggungan'] }}</td>
                                            <td>{{ $penduduk['kondisi_rumah'] }}</td>
                                            <td>{{ $penduduk['status_kepemilikan'] }}</td>
                                            <td>{{ $penduduk['jumlah_penghasilan'] }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Mapping Centroid Tab -->
                        <div class="tab-pane fade" id="mapping" role="tabpanel">
                            <div class="mb-3">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addMappingModal">
                                    Tambah Mapping
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Data ke-</th>
                                            <th>Nama Penduduk</th>
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
                                        @foreach($convertedMappings as $mapping)
                                        <tr>
                                            <td>{{ $mapping['data_ke'] }}</td>
                                            <td>{{ $mapping['nama_penduduk'] }}</td>
                                            <td>
                                                <select class="form-control cluster-select" data-id="{{ $mapping['id'] }}">
                                                    <option value="C1" {{ $mapping['cluster'] == 'C1' ? 'selected' : '' }}>C1</option>
                                                    <option value="C2" {{ $mapping['cluster'] == 'C2' ? 'selected' : '' }}>C2</option>
                                                    <option value="C3" {{ $mapping['cluster'] == 'C3' ? 'selected' : '' }}>C3</option>
                                                </select>
                                            </td>
                                            <td>{{ $mapping['usia'] }}</td>
                                            <td>{{ $mapping['jumlah_tanggungan'] }}</td>
                                            <td>{{ $mapping['kondisi_rumah'] }}</td>
                                            <td>{{ $mapping['status_kepemilikan'] }}</td>
                                            <td>{{ $mapping['jumlah_penghasilan'] }}</td>
                                            <td>
                                                <form action="{{ route('admin.mapping-centroid.destroy', $mapping['id']) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Clustering Tab -->
                        <div class="tab-pane fade" id="clustering" role="tabpanel">
                            <div class="mb-3">
                                <form action="{{ route('admin.centroid.calculate') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Hitung Jarak</button>
                                </form>
                            </div>
                            @if(!empty($distanceResults))
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Data ke-</th>
                                            <th>Nama Penduduk</th>
                                            <th>Jarak ke C1</th>
                                            <th>Jarak ke C2</th>
                                            <th>Jarak ke C3</th>
                                            <th>Cluster</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($distanceResults as $result)
                                        <tr>
                                            <td>{{ $result['penduduk']->id }}</td>
                                            <td>{{ $result['penduduk']->nama }}</td>
                                            @foreach($result['distances'] as $distance)
                                            <td>{{ number_format($distance, 2) }}</td>
                                            @endforeach
                                            <td>C{{ array_search(min($result['distances']), $result['distances']) + 1 }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Mapping Modal -->
<div class="modal fade" id="addMappingModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Mapping</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.mapping-centroid.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Pilih Penduduk</label>
                        <select name="data_ke" class="form-control" id="penduduk-select" required>
                            <option value="">Pilih Penduduk</option>
                            @foreach($convertedPenduduks as $penduduk)
                                <option value="{{ $penduduk['id'] }}" 
                                    data-nama="{{ $penduduk['nama'] }}"
                                    data-usia="{{ $penduduk['usia'] }}"
                                    data-tanggungan="{{ $penduduk['jumlah_tanggungan'] }}"
                                    data-kondisi-rumah="{{ $penduduk['kondisi_rumah'] }}"
                                    data-status-kepemilikan="{{ $penduduk['status_kepemilikan'] }}"
                                    data-penghasilan="{{ $penduduk['jumlah_penghasilan'] }}">
                                    {{ $penduduk['nama'] }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="nama_penduduk" id="nama_penduduk">
                        <input type="hidden" name="usia" id="usia">
                        <input type="hidden" name="jumlah_tanggungan" id="jumlah_tanggungan">
                        <input type="hidden" name="kondisi_rumah" id="kondisi_rumah">
                        <input type="hidden" name="status_kepemilikan" id="status_kepemilikan">
                        <input type="hidden" name="jumlah_penghasilan" id="jumlah_penghasilan">
                    </div>
                    <div class="form-group">
                        <label>Cluster</label>
                        <select name="cluster" class="form-control" required>
                            <option value="">Pilih Cluster</option>
                            <option value="C1">C1</option>
                            <option value="C2">C2</option>
                            <option value="C3">C3</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Handle penduduk selection in modal
    $('#penduduk-select').change(function() {
        var selectedOption = $(this).find('option:selected');
        if (selectedOption.val()) {
            // Set all hidden input values
            $('#nama_penduduk').val(selectedOption.data('nama'));
            $('#usia').val(selectedOption.data('usia'));
            $('#jumlah_tanggungan').val(selectedOption.data('tanggungan'));
            $('#kondisi_rumah').val(selectedOption.data('kondisi-rumah'));
            $('#status_kepemilikan').val(selectedOption.data('status-kepemilikan'));
            $('#jumlah_penghasilan').val(selectedOption.data('penghasilan'));
            
            // Show preview of selected data
            toastr.info('Data penduduk terpilih: ' + selectedOption.text());
        }
    });

    // Handle cluster change
    $('.cluster-select').change(function() {
        var id = $(this).data('id');
        var cluster = $(this).val();
        
        $.ajax({
            url: '/admin/mapping-centroid/' + id,
            method: 'PUT',
            data: {
                _token: '{{ csrf_token() }}',
                cluster: cluster
            },
            success: function(response) {
                toastr.success('Cluster berhasil diperbarui');
            },
            error: function(xhr) {
                toastr.error('Terjadi kesalahan saat memperbarui cluster');
            }
        });
    });
});
</script>
@endpush 