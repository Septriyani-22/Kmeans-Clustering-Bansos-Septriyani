@extends('layouts.admin')

@section('title', 'Tambah Centroid')

@section('content')
<div class="container">
    <div class="header">
        <h1>Tambah Centroid</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.centroid.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="nama_centroid">Nama Centroid</label>
                    <input type="text" class="form-control @error('nama_centroid') is-invalid @enderror" 
                           id="nama_centroid" name="nama_centroid" value="{{ old('nama_centroid') }}" required>
                    @error('nama_centroid')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="penghasilan_num">Penghasilan</label>
                    <select class="form-control @error('penghasilan_num') is-invalid @enderror" 
                            id="penghasilan_num" name="penghasilan_num" required>
                        <option value="">Pilih Penghasilan</option>
                        <option value="1" {{ old('penghasilan_num') == 1 ? 'selected' : '' }}>Kurang Dari 500</option>
                        <option value="2" {{ old('penghasilan_num') == 2 ? 'selected' : '' }}>500 s/d 1 juta</option>
                        <option value="3" {{ old('penghasilan_num') == 3 ? 'selected' : '' }}>Lebih Dari 1 juta</option>
                        <option value="4" {{ old('penghasilan_num') == 4 ? 'selected' : '' }}>Lebih Dari 2 juta</option>
                    </select>
                    @error('penghasilan_num')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="tanggungan_num">Tanggungan</label>
                    <select class="form-control @error('tanggungan_num') is-invalid @enderror" 
                            id="tanggungan_num" name="tanggungan_num" required>
                        <option value="">Pilih Tanggungan</option>
                        <option value="1" {{ old('tanggungan_num') == 1 ? 'selected' : '' }}>1</option>
                        <option value="2" {{ old('tanggungan_num') == 2 ? 'selected' : '' }}>2</option>
                        <option value="3" {{ old('tanggungan_num') == 3 ? 'selected' : '' }}>3</option>
                        <option value="4" {{ old('tanggungan_num') == 4 ? 'selected' : '' }}>4</option>
                        <option value="5" {{ old('tanggungan_num') == 5 ? 'selected' : '' }}>5 lebih</option>
                    </select>
                    @error('tanggungan_num')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                              id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="usia">Usia</label>
                    <input type="number" class="form-control @error('usia') is-invalid @enderror" 
                           id="usia" name="usia" value="{{ old('usia') }}" required>
                    @error('usia')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="kondisi_rumah">Kondisi Rumah</label>
                    <select class="form-control @error('kondisi_rumah') is-invalid @enderror" 
                            id="kondisi_rumah" name="kondisi_rumah" required>
                        <option value="">Pilih Kondisi Rumah</option>
                        <option value="baik" {{ old('kondisi_rumah') == 'baik' ? 'selected' : '' }}>Baik</option>
                        <option value="cukup" {{ old('kondisi_rumah') == 'cukup' ? 'selected' : '' }}>Cukup</option>
                        <option value="kurang" {{ old('kondisi_rumah') == 'kurang' ? 'selected' : '' }}>Kurang</option>
                    </select>
                    @error('kondisi_rumah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status_kepemilikan">Status Kepemilikan</label>
                    <select class="form-control @error('status_kepemilikan') is-invalid @enderror" 
                            id="status_kepemilikan" name="status_kepemilikan" required>
                        <option value="">Pilih Status Kepemilikan</option>
                        <option value="hak milik" {{ old('status_kepemilikan') == 'hak milik' ? 'selected' : '' }}>Hak Milik</option>
                        <option value="sewa" {{ old('status_kepemilikan') == 'sewa' ? 'selected' : '' }}>Sewa</option>
                        <option value="numpang" {{ old('status_kepemilikan') == 'numpang' ? 'selected' : '' }}>Numpang</option>
                    </select>
                    @error('status_kepemilikan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="{{ route('admin.centroid.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.container {
    padding: 20px;
}

.header {
    margin-bottom: 20px;
}

.header h1 {
    margin: 0;
    color: #1f2937;
}

.card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.card-body {
    padding: 20px;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #374151;
}

.form-control {
    display: block;
    width: 100%;
    padding: 0.5rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-control:focus {
    color: #495057;
    background-color: #fff;
    border-color: #80bdff;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}

.is-invalid {
    border-color: #dc3545;
}

.invalid-feedback {
    display: block;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 80%;
    color: #dc3545;
}

.btn {
    display: inline-block;
    font-weight: 400;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    user-select: none;
    border: 1px solid transparent;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: 0.25rem;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.btn-success {
    color: #fff;
    background-color: #28a745;
    border-color: #28a745;
}

.btn-success:hover {
    color: #fff;
    background-color: #218838;
    border-color: #1e7e34;
}

.btn-secondary {
    color: #fff;
    background-color: #6c757d;
    border-color: #6c757d;
}

.btn-secondary:hover {
    color: #fff;
    background-color: #5a6268;
    border-color: #545b62;
}
</style>
@endsection 