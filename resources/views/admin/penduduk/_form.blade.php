<div style="max-width:600px; margin:0 auto;">
    <div style="margin-bottom:18px;">
        <label for="nik">NIK</label>
        <input type="text" name="nik" id="nik" value="{{ old('nik', $penduduk->nik ?? '') }}" required class="form-control">
    </div>
    <div style="margin-bottom:18px;">
        <label for="nama">Nama</label>
        <input type="text" name="nama" id="nama" value="{{ old('nama', $penduduk->nama ?? '') }}" required class="form-control">
    </div>
    <div style="margin-bottom:18px;">
        <label for="tahun">Tahun</label>
        <input type="number" name="tahun" id="tahun" value="{{ old('tahun', $penduduk->tahun ?? '2025') }}" required class="form-control">
    </div>
    <div style="margin-bottom:18px;">
        <label for="jenis_kelamin">Jenis Kelamin</label>
        <select name="jenis_kelamin" id="jenis_kelamin" required class="form-control">
            <option value="">-- Pilih --</option>
            <option value="L" {{ old('jenis_kelamin', $penduduk->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
            <option value="P" {{ old('jenis_kelamin', $penduduk->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
        </select>
    </div>
    <div style="margin-bottom:18px;">
        <label for="usia">Usia</label>
        <input type="number" name="usia" id="usia" value="{{ old('usia', $penduduk->usia ?? '') }}" required class="form-control">
    </div>
    <div style="margin-bottom:18px;">
        <label for="rt">RT</label>
        <input type="number" name="rt" id="rt" value="{{ old('rt', $penduduk->rt ?? '') }}" required class="form-control">
    </div>
    <div style="margin-bottom:18px;">
        <label for="tanggungan">Tanggungan</label>
        <input type="number" name="tanggungan" id="tanggungan" value="{{ old('tanggungan', $penduduk->tanggungan ?? '') }}" required class="form-control">
    </div>
    <div style="margin-bottom:18px;">
        <label for="kondisi_rumah">Kondisi Rumah</label>
        <select name="kondisi_rumah" id="kondisi_rumah" required class="form-control">
            <option value="">-- Pilih --</option>
            <option value="kurang" {{ old('kondisi_rumah', $penduduk->kondisi_rumah ?? '') == 'kurang' ? 'selected' : '' }}>Kurang</option>
            <option value="cukup" {{ old('kondisi_rumah', $penduduk->kondisi_rumah ?? '') == 'cukup' ? 'selected' : '' }}>Cukup</option>
            <option value="baik" {{ old('kondisi_rumah', $penduduk->kondisi_rumah ?? '') == 'baik' ? 'selected' : '' }}>Baik</option>
        </select>
    </div>
    <div style="margin-bottom:18px;">
        <label for="status_kepemilikan">Status Kepemilikan Rumah</label>
        <select name="status_kepemilikan" id="status_kepemilikan" required class="form-control">
            <option value="">-- Pilih --</option>
            <option value="hak milik" {{ old('status_kepemilikan', $penduduk->status_kepemilikan ?? '') == 'hak milik' ? 'selected' : '' }}>Hak Milik</option>
            <option value="numpang" {{ old('status_kepemilikan', $penduduk->status_kepemilikan ?? '') == 'numpang' ? 'selected' : '' }}>Numpang</option>
            <option value="sewa" {{ old('status_kepemilikan', $penduduk->status_kepemilikan ?? '') == 'sewa' ? 'selected' : '' }}>Sewa</option>
        </select>
    </div>
    <div style="margin-bottom:18px;">
        <label for="penghasilan">Penghasilan</label>
        <input type="number" name="penghasilan" id="penghasilan" value="{{ old('penghasilan', $penduduk->penghasilan ?? '') }}" required class="form-control">
    </div>
</div>

<style>
    .form-control {
        padding:7px 12px;
        border:1px solid #d1d5db;
        border-radius:6px;
        font-size:1rem;
        width:100%;
    }
    label {
        display:block;
        margin-bottom:4px;
        color:#888fa6;
    }
</style>
