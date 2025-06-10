@extends('layouts.admin')

@section('title', 'Data Penduduk - BANSOS KMEANS')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Penduduk</h3>
                    <div class="card-tools">
                        <div class="btn-group">
                            <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="{{ route('admin.penduduk.create') }}" class="dropdown-item">
                                    <i class="fas fa-plus"></i> Tambah Data
                                </a>
                                <a href="#" class="dropdown-item" data-toggle="modal" data-target="#importModal">
                                    <i class="fas fa-file-import"></i> Import Excel
                                </a>
                                <a href="{{ route('admin.penduduk.export') }}" class="dropdown-item">
                                    <i class="fas fa-file-export"></i> Export Excel
                                </a>
                                <a href="{{ route('admin.penduduk.print') }}" class="dropdown-item" target="_blank">
                                    <i class="fas fa-print"></i> Print Data
                                </a>
                                <div class="dropdown-divider"></div>
                                <a href="#" class="dropdown-item text-danger" onclick="event.preventDefault(); document.getElementById('clear-clusters-form').submit();">
                                    <i class="fas fa-trash"></i> Hapus Semua Cluster
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Filter Section -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <form action="{{ route('admin.penduduk.index') }}" method="GET" class="form-inline">
                                <div class="form-group mr-2">
                                    <input type="text" name="search" class="form-control" placeholder="Cari NIK/Nama..." value="{{ request('search') }}">
                                </div>
                                <div class="form-group mr-2">
                                    <select name="jenis_kelamin" class="form-control">
                                        <option value="">Semua Jenis Kelamin</option>
                                        <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                                <div class="form-group mr-2">
                                    <select name="rt" class="form-control">
                                        <option value="">Semua RT</option>
                                        @for($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}" {{ request('rt') == $i ? 'selected' : '' }}>RT {{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="form-group mr-2">
                                    <select name="cluster" class="form-control">
                                        <option value="">Semua Cluster</option>
                                        @for($i = 1; $i <= 3; $i++)
                                            <option value="{{ $i }}" {{ request('cluster') == $i ? 'selected' : '' }}>
                                                Cluster {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="form-group mr-2">
                                    <select name="sort" class="form-control">
                                        <option value="">Urutkan</option>
                                        <option value="nama_asc" {{ request('sort') == 'nama_asc' ? 'selected' : '' }}>Nama (A-Z)</option>
                                        <option value="nama_desc" {{ request('sort') == 'nama_desc' ? 'selected' : '' }}>Nama (Z-A)</option>
                                        <option value="usia_asc" {{ request('sort') == 'usia_asc' ? 'selected' : '' }}>Usia (Rendah-Tinggi)</option>
                                        <option value="usia_desc" {{ request('sort') == 'usia_desc' ? 'selected' : '' }}>Usia (Tinggi-Rendah)</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                @if(request()->hasAny(['search', 'jenis_kelamin', 'rt', 'cluster', 'sort']))
                                    <a href="{{ route('admin.penduduk.index') }}" class="btn btn-secondary ml-2">
                                        <i class="fas fa-times"></i> Reset
                                    </a>
                                @endif
                            </form>
                        </div>
                    </div>


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
                                    <th>Rt</th>
                                    <th>Tanggungan</th>
                                    <th>Kondisi Rumah</th>
                                    <th>Status Kepemilikan</th>
                                    <th>Penghasilan</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($penduduk as $p)
                                    <tr>
                                        <td>{{ $p->no ?? $loop->iteration }}</td>
                                        <td>{{ $p->nik }}</td>
                                        <td>{{ $p->nama }}</td>
                                        <td>{{ $p->tahun }}</td>
                                        <td>{{ $p->jenis_kelamin }}</td>
                                        <td>{{ $p->usia }}</td>
                                        <td>{{ $p->rt }}</td>
                                        <td>{{ $p->tanggungan }}</td>
                                        <td>
                                            <span class="badge badge-{{ $p->kondisi_rumah == 'baik' ? 'success' : ($p->kondisi_rumah == 'cukup' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($p->kondisi_rumah) }}
                                            </span>
                                        </td>
                                        <td>{{ ucfirst($p->status_kepemilikan) }}</td>
                                        <td>Rp {{ number_format($p->penghasilan, 0, ',', '.') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.penduduk.edit', $p->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.penduduk.destroy', $p->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="13" class="text-center">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $penduduk->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Data Penduduk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.penduduk.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="file">Pilih File Excel</label>
                        <input type="file" class="form-control-file" id="file" name="file" required>
                        <small class="form-text text-muted">
                            Format file harus .xlsx atau .xls. 
                            <a href="{{ route('admin.penduduk.template') }}">Download template</a>
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Hidden form for clearing clusters -->
<form id="clear-clusters-form" action="{{ route('admin.penduduk.clear-clusters') }}" method="POST" style="display: none;">
    @csrf
    @method('POST')
</form>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTables
        $('.table').DataTable({
            "paging": false,
            "searching": false,
            "ordering": true,
            "info": false,
            "autoWidth": false,
            "responsive": true,
        });
    });
</script>
@endpush
