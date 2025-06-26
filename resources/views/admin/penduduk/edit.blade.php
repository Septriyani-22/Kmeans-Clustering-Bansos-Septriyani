@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Data Penduduk</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.penduduk.update', $penduduk->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="no">No</label>
                                    <input type="number" class="form-control @error('no') is-invalid @enderror" id="no" name="no" value="{{ old('no', $penduduk->no) }}">
                                    @error('no')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="nik">NIK</label>
                                    <input type="text" class="form-control @error('nik') is-invalid @enderror" id="nik" name="nik" value="{{ old('nik', $penduduk->nik) }}" required>
                                    @error('nik')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="nama">Nama</label>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', optional($penduduk->user)->name ?? $penduduk->nama) }}" required>
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="tahun">Tahun</label>
                                    <input type="number" class="form-control @error('tahun') is-invalid @enderror" id="tahun" name="tahun" value="{{ old('tahun', $penduduk->tahun) }}" min="2000" max="2100" required>
                                    @error('tahun')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_lahir">Tanggal Lahir</label>
                                    <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $penduduk->tanggal_lahir) }}" required>
                                    @error('tanggal_lahir')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="jenis_kelamin">Jenis Kelamin</label>
                                    <select class="form-control @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin" name="jenis_kelamin" required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="L" {{ old('jenis_kelamin', $penduduk->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ old('jenis_kelamin', $penduduk->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="usia">Usia</label>
                                    <input type="number" class="form-control @error('usia') is-invalid @enderror" id="usia" name="usia" value="{{ old('usia', $penduduk->usia) }}" required>
                                    @error('usia')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="rt">RT</label>
                                    <input type="number" class="form-control @error('rt') is-invalid @enderror" id="rt" name="rt" value="{{ old('rt', $penduduk->rt) }}" required>
                                    @error('rt')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="tanggungan">Jumlah Tanggungan</label>
                                    <input type="number" class="form-control @error('tanggungan') is-invalid @enderror" id="tanggungan" name="tanggungan" value="{{ old('tanggungan', $penduduk->tanggungan) }}" required>
                                    @error('tanggungan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="kondisi_rumah">Kondisi Rumah</label>
                                    <select class="form-control @error('kondisi_rumah') is-invalid @enderror" id="kondisi_rumah" name="kondisi_rumah" required>
                                        <option value="">Pilih Kondisi Rumah</option>
                                        <option value="baik" {{ old('kondisi_rumah', $penduduk->kondisi_rumah) == 'baik' ? 'selected' : '' }}>Baik</option>
                                        <option value="cukup" {{ old('kondisi_rumah', $penduduk->kondisi_rumah) == 'cukup' ? 'selected' : '' }}>Cukup</option>
                                        <option value="kurang" {{ old('kondisi_rumah', $penduduk->kondisi_rumah) == 'kurang' ? 'selected' : '' }}>Kurang</option>
                                    </select>
                                    @error('kondisi_rumah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="status_kepemilikan">Status Kepemilikan</label>
                                    <select class="form-control @error('status_kepemilikan') is-invalid @enderror" id="status_kepemilikan" name="status_kepemilikan" required>
                                        <option value="">Pilih Status Kepemilikan</option>
                                        <option value="hak milik" {{ old('status_kepemilikan', $penduduk->status_kepemilikan) == 'hak milik' ? 'selected' : '' }}>Hak Milik</option>
                                        <option value="numpang" {{ old('status_kepemilikan', $penduduk->status_kepemilikan) == 'numpang' ? 'selected' : '' }}>Numpang</option>
                                        <option value="sewa" {{ old('status_kepemilikan', $penduduk->status_kepemilikan) == 'sewa' ? 'selected' : '' }}>Sewa</option>
                                    </select>
                                    @error('status_kepemilikan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="penghasilan">Penghasilan</label>
                                    <input type="number" class="form-control @error('penghasilan') is-invalid @enderror" id="penghasilan" name="penghasilan" value="{{ old('penghasilan', $penduduk->penghasilan) }}" required>
                                    @error('penghasilan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Foto KTP</label><br>
                            @if($penduduk->ktp_photo)
                                <img src="{{ asset('storage/'.$penduduk->ktp_photo) }}" alt="Foto KTP" width="120">
                            @else
                                <span class="text-muted">Belum diupload</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Surat Keterangan Tidak Mampu</label><br>
                            @if($penduduk->sktm_file)
                                <a href="{{ asset('storage/'.$penduduk->sktm_file) }}" target="_blank">Lihat File</a>
                            @else
                                <span class="text-muted">Belum diupload</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Bukti Status Kepemilikan Rumah</label><br>
                            @if($penduduk->bukti_kepemilikan_file)
                                <a href="{{ asset('storage/'.$penduduk->bukti_kepemilikan_file) }}" target="_blank">Lihat File</a>
                            @else
                                <span class="text-muted">Belum diupload</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Slip Gaji</label><br>
                            @if($penduduk->slip_gaji_file)
                                <a href="{{ asset('storage/'.$penduduk->slip_gaji_file) }}" target="_blank">Lihat File</a>
                            @else
                                <span class="text-muted">Belum diupload</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Foto Kondisi Rumah</label><br>
                            @if($penduduk->foto_rumah)
                                <img src="{{ asset('storage/'.$penduduk->foto_rumah) }}" alt="Foto Rumah" width="120">
                            @else
                                <span class="text-muted">Belum diupload</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('admin.penduduk.index') }}" class="btn btn-secondary">Kembali</a>
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
        // Format number inputs
        $('#penghasilan').on('input', function() {
            let value = $(this).val();
            if (value) {
                value = parseInt(value.replace(/[^\d]/g, ''));
                $(this).val(value);
            }
        });
    });
</script>
@endpush
