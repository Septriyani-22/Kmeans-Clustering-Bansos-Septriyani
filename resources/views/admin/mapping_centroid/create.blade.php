@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tambah Mapping Centroid</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.mapping-centroid.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="penduduk_id">Penduduk</label>
                            <select name="penduduk_id" id="penduduk_id" class="form-control @error('penduduk_id') is-invalid @enderror" required>
                                <option value="">Pilih Penduduk</option>
                                @foreach($penduduks as $penduduk)
                                    <option value="{{ $penduduk->id }}">{{ $penduduk->nama }}</option>
                                @endforeach
                            </select>
                            @error('penduduk_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="centroid_id">Centroid</label>
                            <select name="centroid_id" id="centroid_id" class="form-control @error('centroid_id') is-invalid @enderror" required>
                                <option value="">Pilih Centroid</option>
                                @foreach($centroids as $centroid)
                                    <option value="{{ $centroid->id }}">{{ $centroid->nama_centroid }}</option>
                                @endforeach
                            </select>
                            @error('centroid_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="jarak_euclidean">Jarak Euclidean</label>
                            <input type="number" step="0.0001" name="jarak_euclidean" id="jarak_euclidean" class="form-control @error('jarak_euclidean') is-invalid @enderror" required>
                            @error('jarak_euclidean')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="cluster">Cluster</label>
                            <input type="number" name="cluster" id="cluster" class="form-control @error('cluster') is-invalid @enderror" required min="1">
                            @error('cluster')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="status_kelayakan">Status Kelayakan</label>
                            <select name="status_kelayakan" id="status_kelayakan" class="form-control @error('status_kelayakan') is-invalid @enderror" required>
                                <option value="Belum Ditentukan">Belum Ditentukan</option>
                                <option value="Layak">Layak</option>
                                <option value="Tidak Layak">Tidak Layak</option>
                            </select>
                            @error('status_kelayakan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3"></textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('admin.mapping-centroid.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 