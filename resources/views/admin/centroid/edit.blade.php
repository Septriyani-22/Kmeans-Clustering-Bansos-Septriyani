@extends('layouts.admin')

@section('title', 'Edit Centroid')

@section('content')
<div class="container">
    <div class="header">
        <h1>Edit Centroid</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.centroid.update', $centroid->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="nama_centroid">Nama Centroid</label>
                    <input type="text" class="form-control @error('nama_centroid') is-invalid @enderror" 
                           id="nama_centroid" name="nama_centroid" value="{{ old('nama_centroid', $centroid->nama_centroid) }}" required>
                    @error('nama_centroid')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="penghasilan_num">Penghasilan</label>
                    <select class="form-control @error('penghasilan_num') is-invalid @enderror" 
                            id="penghasilan_num" name="penghasilan_num" required>
                        <option value="1" {{ $centroid->penghasilan_num == 1 ? 'selected' : '' }}>Kurang Dari 500</option>
                        <option value="2" {{ $centroid->penghasilan_num == 2 ? 'selected' : '' }}>500 s/d 1 juta</option>
                        <option value="3" {{ $centroid->penghasilan_num == 3 ? 'selected' : '' }}>Lebih Dari 1 juta</option>
                        <option value="4" {{ $centroid->penghasilan_num == 4 ? 'selected' : '' }}>Lebih Dari 2 juta</option>
                    </select>
                    @error('penghasilan_num')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="tanggungan_num">Tanggungan</label>
                    <select class="form-control @error('tanggungan_num') is-invalid @enderror" 
                            id="tanggungan_num" name="tanggungan_num" required>
                        <option value="1" {{ $centroid->tanggungan_num == 1 ? 'selected' : '' }}>1</option>
                        <option value="2" {{ $centroid->tanggungan_num == 2 ? 'selected' : '' }}>2</option>
                        <option value="3" {{ $centroid->tanggungan_num == 3 ? 'selected' : '' }}>3</option>
                        <option value="4" {{ $centroid->tanggungan_num == 4 ? 'selected' : '' }}>4</option>
                        <option value="5" {{ $centroid->tanggungan_num == 5 ? 'selected' : '' }}>5 lebih</option>
                    </select>
                    @error('tanggungan_num')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                              id="keterangan" name="keterangan" rows="3">{{ old('keterangan', $centroid->keterangan) }}</textarea>
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Update</button>
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

.btn-primary {
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
}

.btn-primary:hover {
    color: #fff;
    background-color: #0069d9;
    border-color: #0062cc;
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