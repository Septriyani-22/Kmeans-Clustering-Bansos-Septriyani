@extends('layouts.penduduk')

@section('title', 'Update Data Diri')

@section('content')
<div class="container-fluid">
    <form action="{{ route('penduduk.profile.update') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-user-edit mr-1"></i> Data Utama</h3>
                    </div>
                    <div class="card-body">
                        <!-- NAMA LENGKAP -->
                        <div class="form-group">
                            <label for="nama">Nama Lengkap</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" id="nama" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $user->name) }}" required>
                            </div>
                            @error('nama') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- NIK -->
                        <div class="form-group">
                            <label for="nik">NIK</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                </div>
                                <input type="text" id="nik" name="nik" class="form-control @error('nik') is-invalid @enderror" value="{{ old('nik', $penduduk->nik) }}" required>
                            </div>
                            @error('nik') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- EMAIL -->
                        <div class="form-group">
                            <label for="email">Email</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                            </div>
                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- TAHUN & USIA (Side-by-side) -->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="tahun">Tahun</label>
                                    <input type="number" id="tahun" name="tahun" class="form-control @error('tahun') is-invalid @enderror" value="{{ old('tahun', $penduduk->tahun) }}" required>
                                    @error('tahun') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="usia">Usia</label>
                                    <input type="number" id="usia" name="usia" class="form-control @error('usia') is-invalid @enderror" value="{{ old('usia', $penduduk->usia) }}" required>
                                    @error('usia') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- JENIS KELAMIN -->
                        <div class="form-group">
                            <label for="jenis_kelamin">Jenis Kelamin</label>
                            <select id="jenis_kelamin" name="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror" required>
                                <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                <option value="L" {{ old('jenis_kelamin', $penduduk->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $penduduk->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-house-user mr-1"></i> Data Rumah Tangga</h3>
                    </div>
                    <div class="card-body">
                        <!-- RT & TANGGUNGAN -->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="rt">RT</label>
                                    <input type="number" id="rt" name="rt" class="form-control @error('rt') is-invalid @enderror" value="{{ old('rt', $penduduk->rt) }}" required>
                                    @error('rt') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="tanggungan">Jml Tanggungan</label>
                                    <input type="number" id="tanggungan" name="tanggungan" class="form-control @error('tanggungan') is-invalid @enderror" value="{{ old('tanggungan', $penduduk->tanggungan) }}" required>
                                    @error('tanggungan') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- PENGHASILAN -->
                        <div class="form-group">
                            <label for="penghasilan">Penghasilan (per bulan)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" id="penghasilan" name="penghasilan" class="form-control @error('penghasilan') is-invalid @enderror" value="{{ old('penghasilan', $penduduk->penghasilan) }}" required>
                            </div>
                            @error('penghasilan') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- KONDISI RUMAH -->
                        <div class="form-group">
                            <label for="kondisi_rumah">Kondisi Rumah</label>
                            <select id="kondisi_rumah" name="kondisi_rumah" class="form-control @error('kondisi_rumah') is-invalid @enderror" required>
                                <option value="" disabled selected>Pilih Kondisi</option>
                                <option value="baik" {{ old('kondisi_rumah', $penduduk->kondisi_rumah) == 'baik' ? 'selected' : '' }}>Baik</option>
                                <option value="cukup" {{ old('kondisi_rumah', $penduduk->kondisi_rumah) == 'cukup' ? 'selected' : '' }}>Cukup</option>
                                <option value="kurang" {{ old('kondisi_rumah', $penduduk->kondisi_rumah) == 'kurang' ? 'selected' : '' }}>Kurang</option>
                            </select>
                            @error('kondisi_rumah') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- STATUS KEPEMILIKAN -->
                        <div class="form-group">
                            <label for="status_kepemilikan">Status Kepemilikan</label>
                            <select id="status_kepemilikan" name="status_kepemilikan" class="form-control @error('status_kepemilikan') is-invalid @enderror" required>
                                <option value="" disabled selected>Pilih Status</option>
                                <option value="hak milik" {{ old('status_kepemilikan', $penduduk->status_kepemilikan) == 'hak milik' ? 'selected' : '' }}>Hak Milik</option>
                                <option value="numpang" {{ old('status_kepemilikan', $penduduk->status_kepemilikan) == 'numpang' ? 'selected' : '' }}>Numpang</option>
                                <option value="sewa" {{ old('status_kepemilikan', $penduduk->status_kepemilikan) == 'sewa' ? 'selected' : '' }}>Sewa</option>
                            </select>
                            @error('status_kepemilikan') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mb-3">
                <a href="{{ route('penduduk.dashboard') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary float-right">Simpan Perubahan</button>
            </div>
        </div>
    </form>
</div>
@endsection 