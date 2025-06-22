@extends('layouts.admin')

@section('title', 'Clustering - BANSOS KMEANS')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Proses Clustering & Centroid</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.clustering.reset') }}" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin mereset semua data perhitungan, mapping, dan centroid?')">
                            <i class="fas fa-trash"></i> Reset Clustering
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Tab Navigation -->
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="data-penduduk-tab" data-toggle="tab" href="#data-penduduk" role="tab" aria-controls="data-penduduk" aria-selected="true">Data Penduduk (Konversi)</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="mapping-tab" data-toggle="tab" href="#mapping" role="tab" aria-controls="mapping" aria-selected="false">1. Mapping Centroid Awal</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="calculation-tab" data-toggle="tab" href="#calculation" role="tab" aria-controls="calculation" aria-selected="false">2. Perhitungan Jarak & Iterasi</a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="myTabContent">
                        <!-- Data Penduduk Konversi -->
                        <div class="tab-pane fade show active" id="data-penduduk" role="tabpanel" aria-labelledby="data-penduduk-tab">
                             <div class="pt-3">
                                <p>Ini adalah data penduduk asli yang telah dikonversi ke dalam nilai numerik berdasarkan kriteria yang ada.</p>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Data ke-</th>
                                                <th>Nama Penduduk</th>
                                                <th>Usia</th>
                                                <th>Jml Tanggungan</th>
                                                <th>Kondisi Rumah</th>
                                                <th>Status Kepemilikan</th>
                                                <th>Jml Penghasilan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($convertedPenduduks as $penduduk)
                                            <tr>
                                                <td>{{ $penduduk['id'] }}</td>
                                                <td>{{ $penduduk['nama'] }}</td>
                                                <td>{{ $penduduk['usia'] }}</td>
                                                <td>{{ $penduduk['jumlah_tanggungan'] }}</td>
                                                <td>{{ $penduduk['kondisi_rumah'] }}</td>
                                                <td>{{ $penduduk['status_kepemilikan'] }}</td>
                                                <td>{{ $penduduk['jumlah_penghasilan'] }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Data penduduk kosong.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Mapping Centroid Tab -->
                        <div class="tab-pane fade" id="mapping" role="tabpanel" aria-labelledby="mapping-tab">
                            <div class="pt-3">
                                <p>Pilih beberapa data penduduk untuk dijadikan centroid awal bagi setiap cluster. Ini akan menjadi titik awal untuk proses clustering.</p>
                                <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addMappingModal">
                                    <i class="fas fa-plus"></i> Tambah Mapping Centroid
                                </button>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Data ke-</th>
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
                                            @forelse($convertedMappings as $mapping)
                                            <tr>
                                                <td>{{ $mapping['data_ke'] }}</td>
                                                <td>{{ $mapping['nama_penduduk'] }}</td>
                                                <td>
                                                    <span class="badge badge-info">{{ $mapping['cluster'] }}</span>
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
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus mapping ini?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="9" class="text-center">Belum ada data yang di-mapping.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Calculation Tab -->
                        <div class="tab-pane fade" id="calculation" role="tabpanel" aria-labelledby="calculation-tab">
                            <div class="pt-3">
                                <p>Setelah mapping selesai, klik tombol di bawah untuk memulai proses perhitungan K-Means. Centroid awal akan dihitung berdasarkan rata-rata dari data yang Anda mapping.</p>
                                 <button type="button" class="btn btn-success" data-toggle="modal" data-target="#prosesModal">
                                    <i class="fas fa-calculator"></i> Mulai Proses Clustering
                                </button>

                                @if(!empty($distanceResults))
                                    <h4>Hasil Perhitungan Jarak (Iterasi 1)</h4>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>ID Penduduk</th>
                                                    <th>Nama Penduduk</th>
                                                    <th>Jarak ke C1</th>
                                                    <th>Jarak ke C2</th>
                                                    <th>Jarak ke C3</th>
                                                    <th>Cluster Terdekat</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($distanceResults as $result)
                                                    <tr>
                                                        <td>{{ $result['penduduk']->id }}</td>
                                                        <td>{{ $result['penduduk']->nama }}</td>
                                                        <td>{{ $result['c1_distance'] }}</td>
                                                        <td>{{ $result['c2_distance'] }}</td>
                                                        <td>{{ $result['c3_distance'] }}</td>
                                                        <td><span class="badge badge-primary">{{ $result['cluster'] }}</span></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-center">Hasil perhitungan akan ditampilkan di sini.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Mapping Modal -->
<div class="modal fade" id="addMappingModal" tabindex="-1" role="dialog" aria-labelledby="addMappingModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMappingModalLabel">Tambah Mapping Centroid Awal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.mapping-centroid.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="penduduk-select">Pilih Penduduk</label>
                        <select name="data_ke" class="form-control" id="penduduk-select" required>
                            <option value="" data-nama="">-- Pilih salah satu --</option>
                            @foreach($convertedPenduduks as $p)
                                <option value="{{ $p['id'] }}" 
                                    data-nama="{{ $p['nama'] }}"
                                    data-usia="{{ $p['usia'] }}"
                                    data-tanggungan="{{ $p['jumlah_tanggungan'] }}"
                                    data-kondisi-rumah="{{ $p['kondisi_rumah'] }}"
                                    data-status-kepemilikan="{{ $p['status_kepemilikan'] }}"
                                    data-penghasilan="{{ $p['jumlah_penghasilan'] }}">
                                    {{ $p['nama'] }} (ID: {{ $p['id'] }})
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
                        <label for="cluster-select">Tetapkan ke Cluster</label>
                        <select name="cluster" class="form-control" id="cluster-select" required>
                            <option value="">-- Pilih salah satu --</option>
                            <option value="C1">C1 (Membutuhkan)</option>
                            <option value="C2">C2 (Tidak Membutuhkan)</option>
                            <option value="C3">C3 (Prioritas Sedang)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Mapping</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Proses K-Means -->
<div class="modal fade" id="prosesModal" tabindex="-1" role="dialog" aria-labelledby="prosesModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="prosesModalLabel">Proses K-Means Clustering</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.clustering.proses') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="jumlah_iterasi">Jumlah Iterasi Maksimal</label>
                        <input type="number" class="form-control" id="jumlah_iterasi" name="jumlah_iterasi" value="1" min="1" max="100" required>
                        <small>Proses akan berhenti jika cluster sudah stabil atau mencapai iterasi maksimal.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Proses</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function(){
        // Script to stay on active tab after page reload
        $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
            localStorage.setItem('activeTab', $(e.target).attr('href'));
        });
        var activeTab = localStorage.getItem('activeTab');
        if(activeTab){
            $('#myTab a[href="' + activeTab + '"]').tab('show');
        }

        // Script to update hidden fields on select change
        $('#penduduk-select').on('change', function() {
            const selected = $(this).find('option:selected');
            $('#nama_penduduk').val(selected.data('nama'));
            $('#usia').val(selected.data('usia'));
            $('#jumlah_tanggungan').val(selected.data('tanggungan'));
            $('#kondisi_rumah').val(selected.data('kondisi-rumah'));
            $('#status_kepemilikan').val(selected.data('status-kepemilikan'));
            $('#jumlah_penghasilan').val(selected.data('penghasilan'));
        });
    });
</script>
@endpush 