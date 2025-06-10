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
                        <a href="{{ route('admin.penduduk.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Data
                        </a>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#importModal">
                            <i class="fas fa-file-import"></i> Import
                        </button>
                        <a href="{{ route('admin.penduduk.export') }}" class="btn btn-info">
                            <i class="fas fa-file-export"></i> Export
                        </a>
                        <button type="button" class="btn btn-secondary" onclick="window.print()">
                            <i class="fas fa-print"></i> Print
                        </button>
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

                    <form action="{{ route('admin.penduduk.index') }}" method="GET" class="mb-3">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="text" name="search" class="form-control" placeholder="Cari NIK/Nama..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select name="jenis_kelamin" class="form-control">
                                        <option value="">Semua Jenis Kelamin</option>
                                        <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select name="rt" class="form-control">
                                        <option value="">Semua RT</option>
                                        @for($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}" {{ request('rt') == $i ? 'selected' : '' }}>RT {{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select name="sort" class="form-control">
                                        <option value="id" {{ request('sort') == 'id' ? 'selected' : '' }}>ID</option>
                                        <option value="nik" {{ request('sort') == 'nik' ? 'selected' : '' }}>NIK</option>
                                        <option value="nama" {{ request('sort') == 'nama' ? 'selected' : '' }}>Nama</option>
                                        <option value="usia" {{ request('sort') == 'usia' ? 'selected' : '' }}>Usia</option>
                                        <option value="tanggungan" {{ request('sort') == 'tanggungan' ? 'selected' : '' }}>Tanggungan</option>
                                        <option value="penghasilan" {{ request('sort') == 'penghasilan' ? 'selected' : '' }}>Penghasilan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select name="direction" class="form-control">
                                        <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>Ascending</option>
                                        <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>Descending</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <form action="{{ route('admin.penduduk.mass-update') }}" method="POST" id="massUpdateForm">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%">
                                            <input type="checkbox" id="selectAll">
                                        </th>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Usia</th>
                                        <th>RT</th>
                                        <th>Tanggungan</th>
                                        <th>Kondisi Rumah</th>
                                        <th>Status Kepemilikan</th>
                                        <th>Penghasilan</th>
                                        <th>Cluster</th>
                                        <th width="10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($penduduks as $penduduk)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="selected[]" value="{{ $penduduk->id }}">
                                        </td>
                                        <td>{{ $penduduk->nik }}</td>
                                        <td>{{ $penduduk->nama }}</td>
                                        <td>{{ $penduduk->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                        <td>{{ $penduduk->usia }}</td>
                                        <td>RT {{ $penduduk->rt }}</td>
                                        <td>{{ $penduduk->tanggungan }}</td>
                                        <td>
                                            <span class="badge badge-{{ $penduduk->kondisi_rumah == 'baik' ? 'success' : ($penduduk->kondisi_rumah == 'cukup' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($penduduk->kondisi_rumah) }}
                                            </span>
                                        </td>
                                        <td>{{ ucfirst($penduduk->status_kepemilikan) }}</td>
                                        <td>Rp {{ number_format($penduduk->penghasilan, 0, ',', '.') }}</td>
                                        <td>
                                            @if($penduduk->cluster)
                                                <span class="badge badge-info">Cluster {{ $penduduk->cluster }}</span>
                                            @else
                                                <span class="badge badge-secondary">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.penduduk.edit', $penduduk) }}" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.penduduk.destroy', $penduduk) }}" method="POST" class="d-inline">
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
                                        <td colspan="12" class="text-center">Tidak ada data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <select name="action" class="form-control" required>
                                        <option value="">Pilih Aksi</option>
                                        <option value="delete">Hapus</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-danger" id="massUpdateBtn" disabled>
                                    <i class="fas fa-trash"></i> Hapus Terpilih
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="mt-3">
                        <style>
                            .pagination {
                                display: flex;
                                justify-content: center;
                                list-style: none;
                                padding: 0;
                                margin: 0;
                            }
                            .pagination li {
                                margin: 0 5px;
                            }
                            .pagination li a,
                            .pagination li span {
                                display: inline-block;
                                padding: 8px 16px;
                                border: 1px solid #dee2e6;
                                border-radius: 4px;
                                text-decoration: none;
                                color: #007bff;
                                background-color: #fff;
                                font-weight: 500;
                            }
                            .pagination li.disabled span {
                                color: #6c757d;
                                pointer-events: none;
                                background-color: #fff;
                                border-color: #dee2e6;
                            }
                            .pagination li a:hover {
                                background-color: #e9ecef;
                                border-color: #dee2e6;
                            }
                            /* Hide all pagination items except Previous and Next */
                            .pagination li:not(:first-child):not(:last-child) {
                                display: none;
                            }
                        </style>
                        {{ $penduduks->appends(request()->query())->links() }}
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
            <form action="{{ route('admin.penduduk.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Data Penduduk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="file">File Excel/CSV</label>
                        <input type="file" name="file" id="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                        <small class="form-text text-muted">
                            Format file: Excel (.xlsx, .xls) atau CSV (.csv)<br>
                            Kolom yang diperlukan: NIK, Nama, Usia, Tanggungan, Kondisi Rumah, Status Kepemilikan, Penghasilan
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

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Select All Checkbox
        $('#selectAll').change(function() {
            $('input[name="selected[]"]').prop('checked', $(this).prop('checked'));
            updateMassUpdateButton();
        });

        // Individual Checkbox
        $('input[name="selected[]"]').change(function() {
            updateMassUpdateButton();
        });

        // Action Select
        $('select[name="action"]').change(function() {
            updateMassUpdateButton();
        });

        function updateMassUpdateButton() {
            var checkedCount = $('input[name="selected[]"]:checked').length;
            var actionSelected = $('select[name="action"]').val() !== '';
            $('#massUpdateBtn').prop('disabled', !(checkedCount > 0 && actionSelected));
        }
    });
</script>
@endpush
