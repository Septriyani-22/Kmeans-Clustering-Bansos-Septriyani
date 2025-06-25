@extends('layouts.admin')

@section('title', 'Data Penduduk - BANSOS KMEANS')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-end">
                        <div class="col-md-8">
                            <form action="{{ route('admin.penduduk.index') }}" method="GET" class="form-row align-items-end">
                                <div class="form-group col-md-6 mb-2 position-relative">
                                    <input type="text" id="search-input" name="search" class="form-control" placeholder="Cari NIK/Nama/Tahun/JK/Usia/RT/Tanggungan/Kondisi/Status/Penghasilan..." value="{{ request('search') }}" autocomplete="off">
                                    <div id="autocomplete-list" class="autocomplete-items" style="position:absolute;z-index:1000;width:100%;background:#fff;border:1px solid #ccc;display:none;"></div>
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-filter"></i> Cari
                                    </button>
                                </div>
                                @if(request()->has('search'))
                                    <div class="form-group col-md-2 mb-2">
                                        <a href="{{ route('admin.penduduk.index') }}" class="btn btn-secondary w-100">
                                            <i class="fas fa-times"></i> Reset
                                        </a>
                                    </div>
                                @endif
                            </form>
                        </div>
                        <div class="col-md-4 d-flex flex-wrap justify-content-md-end mt-2 mt-md-0">
                            <div class="btn-group" role="group" aria-label="Aksi Penduduk">
                                <a href="{{ route('admin.penduduk.create') }}" class="btn btn-success mr-2 mb-2 mb-md-0">
                                    <i class="fas fa-plus"></i> Tambah Data
                                </a>
                                <a href="#" class="btn btn-info mr-2 mb-2 mb-md-0" data-toggle="modal" data-target="#importModal">
                                    <i class="fas fa-file-import"></i> Import Excel
                                </a>
                                <a href="{{ route('admin.penduduk.export') }}" class="btn btn-primary mr-2 mb-2 mb-md-0">
                                    <i class="fas fa-file-export"></i> Export Excel
                                </a>
                                <a href="{{ route('admin.penduduk.print') }}" class="btn btn-secondary mb-2 mb-md-0" target="_blank">
                                    <i class="fas fa-print"></i> Print Data
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success') && session('success') !== 'Semua data cluster berhasil dihapus!')
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

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th><a href="{{ route('admin.penduduk.index', array_merge(request()->except('sort', 'page'), ['sort' => request('sort') == 'no_asc' ? 'no_desc' : 'no_asc'])) }}">No{!! request('sort') == 'no_asc' ? ' ▲' : (request('sort') == 'no_desc' ? ' ▼' : '') !!}</a></th>
                                    <th><a href="{{ route('admin.penduduk.index', array_merge(request()->except('sort', 'page'), ['sort' => request('sort') == 'nik_asc' ? 'nik_desc' : 'nik_asc'])) }}">NIK{!! request('sort') == 'nik_asc' ? ' ▲' : (request('sort') == 'nik_desc' ? ' ▼' : '') !!}</a></th>
                                    <th><a href="{{ route('admin.penduduk.index', array_merge(request()->except('sort', 'page'), ['sort' => request('sort') == 'nama_asc' ? 'nama_desc' : 'nama_asc'])) }}">Nama{!! request('sort') == 'nama_asc' ? ' ▲' : (request('sort') == 'nama_desc' ? ' ▼' : '') !!}</a></th>
                                    <th><a href="{{ route('admin.penduduk.index', array_merge(request()->except('sort', 'page'), ['sort' => request('sort') == 'tahun_asc' ? 'tahun_desc' : 'tahun_asc'])) }}">Tahun{!! request('sort') == 'tahun_asc' ? ' ▲' : (request('sort') == 'tahun_desc' ? ' ▼' : '') !!}</a></th>
                                    <th><a href="{{ route('admin.penduduk.index', array_merge(request()->except('sort', 'page'), ['sort' => request('sort') == 'jenis_kelamin_asc' ? 'jenis_kelamin_desc' : 'jenis_kelamin_asc'])) }}">Jenis Kelamin{!! request('sort') == 'jenis_kelamin_asc' ? ' ▲' : (request('sort') == 'jenis_kelamin_desc' ? ' ▼' : '') !!}</a></th>
                                    <th><a href="{{ route('admin.penduduk.index', array_merge(request()->except('sort', 'page'), ['sort' => request('sort') == 'usia_asc' ? 'usia_desc' : 'usia_asc'])) }}">Usia{!! request('sort') == 'usia_asc' ? ' ▲' : (request('sort') == 'usia_desc' ? ' ▼' : '') !!}</a></th>
                                    <th><a href="{{ route('admin.penduduk.index', array_merge(request()->except('sort', 'page'), ['sort' => request('sort') == 'rt_asc' ? 'rt_desc' : 'rt_asc'])) }}">Rt{!! request('sort') == 'rt_asc' ? ' ▲' : (request('sort') == 'rt_desc' ? ' ▼' : '') !!}</a></th>
                                    <th><a href="{{ route('admin.penduduk.index', array_merge(request()->except('sort', 'page'), ['sort' => request('sort') == 'tanggungan_asc' ? 'tanggungan_desc' : 'tanggungan_asc'])) }}">Tanggungan{!! request('sort') == 'tanggungan_asc' ? ' ▲' : (request('sort') == 'tanggungan_desc' ? ' ▼' : '') !!}</a></th>
                                    <th><a href="{{ route('admin.penduduk.index', array_merge(request()->except('sort', 'page'), ['sort' => request('sort') == 'kondisi_rumah_asc' ? 'kondisi_rumah_desc' : 'kondisi_rumah_asc'])) }}">Kondisi Rumah{!! request('sort') == 'kondisi_rumah_asc' ? ' ▲' : (request('sort') == 'kondisi_rumah_desc' ? ' ▼' : '') !!}</a></th>
                                    <th><a href="{{ route('admin.penduduk.index', array_merge(request()->except('sort', 'page'), ['sort' => request('sort') == 'status_kepemilikan_asc' ? 'status_kepemilikan_desc' : 'status_kepemilikan_asc'])) }}">Status Kepemilikan{!! request('sort') == 'status_kepemilikan_asc' ? ' ▲' : (request('sort') == 'status_kepemilikan_desc' ? ' ▼' : '') !!}</a></th>
                                    <th><a href="{{ route('admin.penduduk.index', array_merge(request()->except('sort', 'page'), ['sort' => request('sort') == 'penghasilan_asc' ? 'penghasilan_desc' : 'penghasilan_asc'])) }}">Penghasilan{!! request('sort') == 'penghasilan_asc' ? ' ▲' : (request('sort') == 'penghasilan_desc' ? ' ▼' : '') !!}</a></th>
                                    <th>Foto KTP</th>
                                    <th>SKTM</th>
                                    <th>Bukti Kepemilikan</th>
                                    <th>Slip Gaji</th>
                                    <th>Foto Rumah</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($penduduk as $p)
                                    <tr>
                                        <td>{{ $loop->iteration + ($penduduk->currentPage() - 1) * $penduduk->perPage() }}</td>
                                        <td>{{ $p->nik }}</td>
                                        <td>{{ optional($p->user)->name ?? $p->nama }}</td>
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
                                        <td>@if($p->ktp_photo)<a href="{{ asset('storage/'.$p->ktp_photo) }}" target="_blank">Lihat</a>@else-@endif</td>
                                        <td>@if($p->sktm_file)<a href="{{ asset('storage/'.$p->sktm_file) }}" target="_blank">Lihat</a>@else-@endif</td>
                                        <td>@if($p->bukti_kepemilikan_file)<a href="{{ asset('storage/'.$p->bukti_kepemilikan_file) }}" target="_blank">Lihat</a>@else-@endif</td>
                                        <td>@if($p->slip_gaji_file)<a href="{{ asset('storage/'.$p->slip_gaji_file) }}" target="_blank">Lihat</a>@else-@endif</td>
                                        <td>@if($p->foto_rumah)<a href="{{ asset('storage/'.$p->foto_rumah) }}" target="_blank">Lihat</a>@else-@endif</td>
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

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        let timer = null;
        $('#search-input').on('input', function() {
            clearTimeout(timer);
            const query = $(this).val();
            if (query.length < 1) {
                $('#autocomplete-list').hide();
                return;
            }
            timer = setTimeout(function() {
                $.get("{{ route('admin.penduduk.autocomplete') }}", { q: query }, function(data) {
                    let html = '';
                    if (data.length > 0) {
                        data.forEach(function(item) {
                            html += `<div class='autocomplete-suggestion' style='padding:8px;cursor:pointer;'>${item.display}</div>`;
                        });
                        $('#autocomplete-list').html(html).show();
                    } else {
                        $('#autocomplete-list').hide();
                    }
                });
            }, 200);
        });
        $(document).on('click', '.autocomplete-suggestion', function() {
            $('#search-input').val($(this).text());
            $('#autocomplete-list').hide();
            $('form').submit();
        });
        $(document).click(function(e) {
            if (!$(e.target).closest('#search-input, #autocomplete-list').length) {
                $('#autocomplete-list').hide();
            }
        });
    });
</script>
<style>
.autocomplete-items { max-height: 200px; overflow-y: auto; border-radius: 0 0 6px 6px; }
.autocomplete-suggestion:hover { background: #f1f1f1; }
</style>
@endpush
