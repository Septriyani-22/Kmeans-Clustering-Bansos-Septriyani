@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tambah Mapping Centsroid</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.mapping-centroid.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="data_ke">Pilih Penduduk</label>
                            <select name="data_ke" id="data_ke" class="form-control @error('data_ke') is-invalid @enderror" required>
                                <option value="">Pilih Penduduk</option>
                                @foreach($penduduks as $penduduk)
                                    <option value="{{ $penduduk->id }}">{{ $penduduk->nama }}</option>
                                @endforeach
                            </select>
                            @error('data_ke')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="cluster">Cluster</label>
                            <select name="cluster" id="cluster" class="form-control @error('cluster') is-invalid @enderror" required>
                                <option value="">Pilih Cluster</option>
                                <option value="C1">C1 (Rendah)</option>
                                <option value="C2">C2 (Tinggi)</option>
                                <option value="C3">C3 (Menengah)</option>
                            </select>
                            @error('cluster')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <input type="hidden" name="nama_penduduk" id="nama_penduduk">
                        <input type="hidden" name="usia" id="usia">
                        <input type="hidden" name="jumlah_tanggungan" id="jumlah_tanggungan">
                        <input type="hidden" name="kondisi_rumah" id="kondisi_rumah">
                        <input type="hidden" name="status_kepemilikan" id="status_kepemilikan">
                        <input type="hidden" name="jumlah_penghasilan" id="jumlah_penghasilan">

                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('admin.mapping-centroid.index') }}" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#data_ke').change(function() {
        var pendudukId = $(this).val();
        if (pendudukId) {
            $.ajax({
                url: '/admin/mapping-centroid/get-penduduk/' + pendudukId,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        var data = response.data;
                        $('#nama_penduduk').val(data.nama);
                        $('#usia').val(data.usia);
                        $('#jumlah_tanggungan').val(data.tanggungan);
                        $('#kondisi_rumah').val(data.kondisi_rumah);
                        $('#status_kepemilikan').val(data.status_kepemilikan);
                        $('#jumlah_penghasilan').val(data.penghasilan);
                    }
                },
                error: function(xhr) {
                    console.error('Error:', xhr);
                }
            });
        }
    });
});
</script>
@endpush
@endsection 