<div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:18px;">
    <div>
        <label for="nik">NIK:</label>
        <input type="text" id="nik" name="nik" value="{{ old('nik', $penduduk->nik ?? '') }}" required class="input-style">
    </div>
    <div>
        <label for="nama">Nama:</label>
        <input type="text" id="nama" name="nama" value="{{ old('nama', $penduduk->nama ?? '') }}" required class="input-style">
    </div>
    <div>
        <label for="tahun">Tahun:</label>
        <input type="number" id="tahun" name="tahun" value="{{ old('tahun', $penduduk->tahun ?? '') }}" required class="input-style">
    </div>
    <div>
        <label for="periode">Periode:</label>
        <input type="text" id="periode" name="periode" value="{{ old('periode', $penduduk->periode ?? '') }}" required class="input-style">
    </div>
    <div>
        <label for="kps">KPS:</label>
        <select name="kps" id="kps" class="input-style">
            <option value="Ya" {{ old('kps', $penduduk->kps ?? '') == 'Ya' ? 'selected' : '' }}>Ya</option>
            <option value="Tidak" {{ old('kps', $penduduk->kps ?? '') == 'Tidak' ? 'selected' : '' }}>Tidak</option>
        </select>
    </div>
    <div>
        <label for="status_perkawinan">Status Perkawinan:</label>
        <input type="text" id="status_perkawinan" name="status_perkawinan" value="{{ old('status_perkawinan', $penduduk->status_perkawinan ?? '') }}" required class="input-style">
    </div>
    <div>
        <label for="umur">Umur:</label>
        <input type="number" id="umur" name="umur" value="{{ old('umur', $penduduk->umur ?? '') }}" required class="input-style">
    </div>
    <div>
        <label for="jumlah_tanggungan">Jumlah Tanggungan:</label>
        <input type="number" id="jumlah_tanggungan" name="jumlah_tanggungan" value="{{ old('jumlah_tanggungan', $penduduk->jumlah_tanggungan ?? '') }}" required class="input-style">
    </div>
    <div>
        <label for="pekerjaan">Pekerjaan:</label>
        <input type="text" id="pekerjaan" name="pekerjaan" value="{{ old('pekerjaan', $penduduk->pekerjaan ?? '') }}" class="input-style">
    </div>
    <div>
        <label for="penghasilan">Penghasilan:</label>
        <input type="number" id="penghasilan" name="penghasilan" value="{{ old('penghasilan', $penduduk->penghasilan ?? '') }}" class="input-style">
    </div>
    <div>
        <label for="status_kepemilikan_rumah">Status Kepemilikan Rumah:</label>
        <input type="text" id="status_kepemilikan_rumah" name="status_kepemilikan_rumah" value="{{ old('status_kepemilikan_rumah', $penduduk->status_kepemilikan_rumah ?? '') }}" class="input-style">
    </div>
    <div>
        <label for="luas_bangunan">Luas Bangunan:</label>
        <input type="number" id="luas_bangunan" name="luas_bangunan" value="{{ old('luas_bangunan', $penduduk->luas_bangunan ?? '') }}" class="input-style">
    </div>
    <div>
        <label for="kondisi_rumah">Kondisi Rumah:</label>
        <input type="text" id="kondisi_rumah" name="kondisi_rumah" value="{{ old('kondisi_rumah', $penduduk->kondisi_rumah ?? '') }}" class="input-style">
    </div>
    <div>
        <label for="jaringan_listrik">Jaringan Listrik:</label>
        <input type="text" id="jaringan_listrik" name="jaringan_listrik" value="{{ old('jaringan_listrik', $penduduk->jaringan_listrik ?? '') }}" class="input-style">
    </div>
    <div>
        <label for="sumber_air">Sumber Air:</label>
        <input type="text" id="sumber_air" name="sumber_air" value="{{ old('sumber_air', $penduduk->sumber_air ?? '') }}" class="input-style">
    </div>
    <div>
        <label for="kepemilikan_kendaraan">Kepemilikan Kendaraan:</label>
        <input type="text" id="kepemilikan_kendaraan" name="kepemilikan_kendaraan" value="{{ old('kepemilikan_kendaraan', $penduduk->kepemilikan_kendaraan ?? '') }}" class="input-style">
    </div>
</div>

<style>
    .input-style {
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
