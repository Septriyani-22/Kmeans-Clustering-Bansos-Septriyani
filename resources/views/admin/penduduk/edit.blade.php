@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Data Penduduk</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.penduduk.index') }}" class="btn btn-secondary">
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

                    <form action="{{ route('admin.penduduk.update', $penduduk) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nik">NIK <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nik') is-invalid @enderror" id="nik" name="nik" value="{{ old('nik', $penduduk->nik) }}" required>
                                    @error('nik')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama">Nama <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $penduduk->nama) }}" required>
                                    @error('nama')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jenis_kelamin">Jenis Kelamin <span class="text-danger">*</span></label>
                                    <select class="form-control @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin" name="jenis_kelamin" required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="L" {{ old('jenis_kelamin', $penduduk->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ old('jenis_kelamin', $penduduk->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="usia">Usia <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('usia') is-invalid @enderror" id="usia" name="usia" value="{{ old('usia', $penduduk->usia) }}" min="0" required>
                                    @error('usia')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="rt">RT <span class="text-danger">*</span></label>
                                    <select class="form-control @error('rt') is-invalid @enderror" id="rt" name="rt" required>
                                        <option value="">Pilih RT</option>
                                        @for($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}" {{ old('rt', $penduduk->rt) == $i ? 'selected' : '' }}>RT {{ $i }}</option>
                                        @endfor
                                    </select>
                                    @error('rt')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggungan">Tanggungan <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('tanggungan') is-invalid @enderror" id="tanggungan" name="tanggungan" value="{{ old('tanggungan', $penduduk->tanggungan) }}" min="1" required>
                                    @error('tanggungan')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kondisi_rumah">Kondisi Rumah <span class="text-danger">*</span></label>
                                    <select class="form-control @error('kondisi_rumah') is-invalid @enderror" id="kondisi_rumah" name="kondisi_rumah" required>
                                        <option value="">Pilih Kondisi Rumah</option>
                                        <option value="kurang" {{ old('kondisi_rumah', $penduduk->kondisi_rumah) == 'kurang' ? 'selected' : '' }}>Kurang</option>
                                        <option value="cukup" {{ old('kondisi_rumah', $penduduk->kondisi_rumah) == 'cukup' ? 'selected' : '' }}>Cukup</option>
                                        <option value="baik" {{ old('kondisi_rumah', $penduduk->kondisi_rumah) == 'baik' ? 'selected' : '' }}>Baik</option>
                                    </select>
                                    @error('kondisi_rumah')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status_kepemilikan">Status Kepemilikan <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status_kepemilikan') is-invalid @enderror" id="status_kepemilikan" name="status_kepemilikan" required>
                                        <option value="">Pilih Status Kepemilikan</option>
                                        <option value="hak milik" {{ old('status_kepemilikan', $penduduk->status_kepemilikan) == 'hak milik' ? 'selected' : '' }}>Hak Milik</option>
                                        <option value="numpang" {{ old('status_kepemilikan', $penduduk->status_kepemilikan) == 'numpang' ? 'selected' : '' }}>Numpang</option>
                                        <option value="sewa" {{ old('status_kepemilikan', $penduduk->status_kepemilikan) == 'sewa' ? 'selected' : '' }}>Sewa</option>
                                    </select>
                                    @error('status_kepemilikan')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="penghasilan">Penghasilan <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="number" class="form-control @error('penghasilan') is-invalid @enderror" id="penghasilan" name="penghasilan" value="{{ old('penghasilan', $penduduk->penghasilan) }}" min="0" required>
                                    </div>
                                    @error('penghasilan')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tahun">Tahun <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('tahun') is-invalid @enderror" id="tahun" name="tahun" value="{{ old('tahun', $penduduk->tahun) }}" required>
                                    @error('tahun')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
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
        // Format input penghasilan
        $('#penghasilan').on('input', function() {
            let value = $(this).val();
            if (value < 0) {
                $(this).val(0);
            }
        });
    });
</script>
@endpush
