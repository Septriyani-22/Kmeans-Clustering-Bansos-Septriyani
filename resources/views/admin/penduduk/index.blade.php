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
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#importModal">
                                <i class="fas fa-file-import"></i> Import Data
                            </button>
                            <a href="{{ route('admin.penduduk.export') }}" class="btn btn-success">
                                <i class="fas fa-file-export"></i> Export Data
                            </a>
                            <a href="{{ route('admin.penduduk.cetak') }}" target="_blank" class="btn btn-info">
                                <i class="fas fa-print"></i> Cetak
                            </a>
                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#massUpdateModal">
                                <i class="fas fa-edit"></i> Mass Update
                            </button>
                            <button type="button" class="btn btn-danger" id="massDeleteBtn">
                                <i class="fas fa-trash"></i> Mass Delete
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search and Filter -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <form action="{{ route('admin.penduduk.index') }}" method="GET" class="form-inline">
                                <div class="form-group mx-sm-3">
                                    <input type="text" class="form-control" name="search" placeholder="Cari NIK/Nama..." value="{{ request('search') }}">
                                </div>
                                <div class="form-group mx-sm-3">
                                    <select class="form-control" name="jenis_kelamin">
                                        <option value="">Semua Jenis Kelamin</option>
                                        <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                                <div class="form-group mx-sm-3">
                                    <input type="number" class="form-control" name="rt" placeholder="RT" value="{{ request('rt') }}">
                                </div>
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="5%">
                                        <input type="checkbox" id="selectAll">
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'nik', 'direction' => request('sort') == 'nik' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-dark">
                                            NIK
                                            @if(request('sort') == 'nik')
                                                @if(request('direction') == 'asc')
                                                    <i class="fas fa-sort-up"></i>
                                                @else
                                                    <i class="fas fa-sort-down"></i>
                                                @endif
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'nama', 'direction' => request('sort') == 'nama' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-dark">
                                            Nama
                                            @if(request('sort') == 'nama')
                                                @if(request('direction') == 'asc')
                                                    <i class="fas fa-sort-up"></i>
                                                @else
                                                    <i class="fas fa-sort-down"></i>
                                                @endif
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'tahun', 'direction' => request('sort') == 'tahun' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-dark">
                                            Tahun
                                            @if(request('sort') == 'tahun')
                                                @if(request('direction') == 'asc')
                                                    <i class="fas fa-sort-up"></i>
                                                @else
                                                    <i class="fas fa-sort-down"></i>
                                                @endif
                                            @endif
                                        </a>
                                    </th>
                                    <th>Jenis Kelamin</th>
                                    <th>Usia</th>
                                    <th>RT</th>
                                    <th>Tanggungan</th>
                                    <th>Penghasilan</th>
                                    <th>Kondisi Rumah</th>
                                    <th>Status Kepemilikan</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($penduduk as $p)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="penduduk-checkbox" value="{{ $p->id }}">
                                    </td>
                                    <td>{{ $p->nik }}</td>
                                    <td>{{ $p->nama }}</td>
                                    <td>{{ $p->tahun }}</td>
                                    <td>{{ $p->jenis_kelamin }}</td>
                                    <td>{{ $p->usia }}</td>
                                    <td>{{ $p->rt }}</td>
                                    <td>{{ $p->tanggungan }}</td>
                                    <td>Rp {{ number_format($p->penghasilan, 0, ',', '.') }}</td>
                                    <td>{{ $p->kondisi_rumah }}</td>
                                    <td>{{ $p->status_kepemilikan }}</td>
                                    <td>
                                        <a href="{{ route('admin.penduduk.edit', $p->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.penduduk.destroy', $p->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
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
                        <label for="file">Pilih File Excel/CSV</label>
                        <input type="file" class="form-control-file" id="file" name="file" required>
                        <small class="form-text text-muted">Format file: xlsx, xls, atau csv</small>
                    </div>
                    <div class="form-group">
                        <a href="{{ route('admin.penduduk.format') }}" class="btn btn-info btn-sm">
                            <i class="fas fa-download"></i> Download Template
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Mass Update Modal -->
<div class="modal fade" id="massUpdateModal" tabindex="-1" role="dialog" aria-labelledby="massUpdateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="massUpdateModalLabel">Mass Update Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="massUpdateForm">
                    @csrf
                    <div class="form-group">
                        <label for="field">Field yang akan diupdate</label>
                        <select class="form-control" id="field" name="field" required>
                            <option value="">Pilih Field</option>
                            <option value="tahun">Tahun</option>
                            <option value="jenis_kelamin">Jenis Kelamin</option>
                            <option value="rt">RT</option>
                            <option value="kondisi_rumah">Kondisi Rumah</option>
                            <option value="status_kepemilikan">Status Kepemilikan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="value">Nilai Baru</label>
                        <input type="text" class="form-control" id="value" name="value" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="massUpdateBtn">Update</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Select All Checkbox
    $('#selectAll').change(function() {
        $('.penduduk-checkbox').prop('checked', $(this).prop('checked'));
    });

    // Mass Update
    $('#massUpdateBtn').click(function() {
        var selectedIds = $('.penduduk-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedIds.length === 0) {
            alert('Pilih minimal satu data untuk diupdate');
            return;
        }

        var field = $('#field').val();
        var value = $('#value').val();

        if (!field || !value) {
            alert('Field dan nilai harus diisi');
            return;
        }

        $.ajax({
            url: '{{ route("admin.penduduk.mass-update") }}',
            type: 'POST',
            data: {
                ids: selectedIds,
                field: field,
                value: value,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    alert('Data berhasil diupdate');
                    location.reload();
                } else {
                    alert('Gagal mengupdate data: ' + response.message);
                }
            },
            error: function(xhr) {
                alert('Terjadi kesalahan: ' + (xhr.responseJSON ? xhr.responseJSON.message : 'Unknown error'));
            }
        });
    });

    // Mass Delete
    $('#massDeleteBtn').click(function() {
        var selectedIds = $('.penduduk-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedIds.length === 0) {
            alert('Pilih minimal satu data untuk dihapus');
            return;
        }

        if (confirm('Apakah Anda yakin ingin menghapus ' + selectedIds.length + ' data yang dipilih?')) {
            $.ajax({
                url: '{{ route("admin.penduduk.mass-delete") }}',
                type: 'POST',
                data: {
                    ids: selectedIds,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        alert('Data berhasil dihapus');
                        location.reload();
                    } else {
                        alert('Gagal menghapus data: ' + response.message);
                    }
                },
                error: function(xhr) {
                    alert('Terjadi kesalahan: ' + (xhr.responseJSON ? xhr.responseJSON.message : 'Unknown error'));
                }
            });
        }
    });
});
</script>
@endpush
