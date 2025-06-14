@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Mapping Centroid</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.centroid.mapping.update', $mapping) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="penduduk_id">Penduduk</label>
                            <select name="penduduk_id" id="penduduk_id" class="form-control @error('penduduk_id') is-invalid @enderror">
                                <option value="">Pilih Penduduk</option>
                                @foreach($penduduks as $penduduk)
                                    <option value="{{ $penduduk->id }}" {{ old('penduduk_id', $mapping->penduduk_id) == $penduduk->id ? 'selected' : '' }}>
                                        {{ $penduduk->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('penduduk_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="centroid_id">Centroid</label>
                            <select name="centroid_id" id="centroid_id" class="form-control @error('centroid_id') is-invalid @enderror">
                                <option value="">Pilih Centroid</option>
                                @foreach($centroids as $centroid)
                                    <option value="{{ $centroid->id }}" {{ old('centroid_id', $mapping->centroid_id) == $centroid->id ? 'selected' : '' }}>
                                        Centroid {{ $centroid->id }}
                                    </option>
                                @endforeach
                            </select>
                            @error('centroid_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="jarak_euclidean">Jarak Euclidean</label>
                            <input type="number" step="0.0001" name="jarak_euclidean" id="jarak_euclidean" class="form-control @error('jarak_euclidean') is-invalid @enderror" value="{{ old('jarak_euclidean', $mapping->jarak_euclidean) }}">
                            @error('jarak_euclidean')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="cluster">Cluster</label>
                            <input type="number" name="cluster" id="cluster" class="form-control @error('cluster') is-invalid @enderror" value="{{ old('cluster', $mapping->cluster) }}">
                            @error('cluster')
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
    </div>
</div>
@endsection 