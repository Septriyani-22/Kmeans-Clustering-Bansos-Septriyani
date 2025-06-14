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
                                    <label for="nama">Nama Kriteria</label>
                                    <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $kriteria->nama) }}" required>
                                    @error('nama')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tipe_kriteria">Tipe Kriteria</label>
                                    <select name="tipe_kriteria" id="tipe_kriteria" class="form-control @error('tipe_kriteria') is-invalid @enderror" required>
                                        <option value="">Pilih Tipe Kriteria</option>
                                        @foreach(\App\Models\Kriteria::getTipeKriteria() as $tipe)
                                            <option value="{{ $tipe }}" {{ old('tipe_kriteria', $kriteria->tipe_kriteria) == $tipe ? 'selected' : '' }}>
                                                {{ $tipe }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('tipe_kriteria')
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
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" name="nilai[{{ $index }}][nama]" placeholder="Nama" value="{{ $nilai->nama }}" required>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" class="form-control" name="nilai[{{ $index }}][nilai]" placeholder="Nilai" min="1" value="{{ $nilai->nilai }}" required>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" class="form-control" name="nilai[{{ $index }}][nilai_min]" placeholder="Min" step="0.01" value="{{ $nilai->nilai_min }}">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" class="form-control" name="nilai[{{ $index }}][nilai_max]" placeholder="Max" step="0.01" value="{{ $nilai->nilai_max }}">
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
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="nilai[${nilaiCount}][nama]" placeholder="Nama" required>
                    </div>
                    <div class="col-md-2">
                        <input type="number" class="form-control" name="nilai[${nilaiCount}][nilai]" placeholder="Nilai" min="1" required>
                    </div>
                    <div class="col-md-2">
                        <input type="number" class="form-control" name="nilai[${nilaiCount}][nilai_min]" placeholder="Min" step="0.01">
                    </div>
                    <div class="col-md-2">
                        <input type="number" class="form-control" name="nilai[${nilaiCount}][nilai_max]" placeholder="Max" step="0.01">
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