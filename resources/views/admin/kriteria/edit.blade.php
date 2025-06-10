@extends('layouts.admin')

@section('title', 'Edit Kriteria - BANSOS KMEANS')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Kriteria</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.kriteria.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.kriteria.update', $kriteria->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama">Nama Kriteria <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $kriteria->nama) }}" required>
                                    @error('nama')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kode">Kode <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('kode') is-invalid @enderror" id="kode" name="kode" value="{{ old('kode', $kriteria->kode) }}" required>
                                    @error('kode')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi', $kriteria->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Nilai Kriteria <span class="text-danger">*</span></label>
                            <div id="nilai-container">
                                @foreach($kriteria->nilaiKriteria as $index => $nilai)
                                <div class="row mb-2">
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="nilai[{{ $index }}][nama]" placeholder="Nama" value="{{ $nilai->nama }}" required>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" class="form-control" name="nilai[{{ $index }}][nilai]" placeholder="Nilai" min="1" value="{{ $nilai->nilai }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="nilai[{{ $index }}][keterangan]" placeholder="Keterangan" value="{{ $nilai->keterangan }}">
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-danger btn-remove" @if($index === 0) style="display: none;" @endif>
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-success mt-2" id="btn-add-nilai">
                                <i class="fas fa-plus"></i> Tambah Nilai
                            </button>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        let nilaiCount = {{ count($kriteria->nilaiKriteria) }};

        $('#btn-add-nilai').click(function() {
            const template = `
                <div class="row mb-2">
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="nilai[${nilaiCount}][nama]" placeholder="Nama" required>
                    </div>
                    <div class="col-md-3">
                        <input type="number" class="form-control" name="nilai[${nilaiCount}][nilai]" placeholder="Nilai" min="1" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="nilai[${nilaiCount}][keterangan]" placeholder="Keterangan">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
            $('#nilai-container').append(template);
            nilaiCount++;
            updateRemoveButtons();
        });

        $(document).on('click', '.btn-remove', function() {
            $(this).closest('.row').remove();
            updateRemoveButtons();
        });

        function updateRemoveButtons() {
            const rows = $('#nilai-container .row');
            if (rows.length > 1) {
                $('.btn-remove').show();
            } else {
                $('.btn-remove').hide();
            }
        }
    });
</script>
@endpush