@extends('layouts.kepala_desa')

@section('title', 'Data Penduduk - BANSOS KMEANS')


@section('content')
<div style="background:#fff; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,0.07); padding:32px 40px; max-width:1200px; margin:0 auto;">
    <h1 style="font-size:2rem; color:#888fa6; font-weight:400; margin-bottom:18px;">Data Penduduk</h1>
    <div style="display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; gap:12px; margin-bottom:18px;">
        <form method="GET" action="" style="display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIK atau Nama..." style="padding:7px 12px; border:1px solid #d1d5db; border-radius:6px; font-size:1rem;">
            <select name="jenis_kelamin" style="padding:7px 12px; border:1px solid #d1d5db; border-radius:6px; font-size:1rem;">
                <option value="">Semua Jenis Kelamin</option>
                <option value="Laki-laki" {{ request('jenis_kelamin')=='Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                <option value="Perempuan" {{ request('jenis_kelamin')=='Perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>
            <input type="text" name="rt_rw" value="{{ request('rt_rw') }}" placeholder="RT/RW" style="padding:7px 12px; border:1px solid #d1d5db; border-radius:6px; font-size:1rem; width:100px;">
            <button type="submit" style="background:#2563eb; color:#fff; border:none; border-radius:6px; padding:7px 16px; font-size:1rem;">Cari/Filter</button>
        </form>
        <div style="display:flex; gap:8px; align-items:center;">
            <form method="POST" action="{{ route('kepala_desa.penduduk.import') }}" enctype="multipart/form-data" style="display:inline-flex; align-items:center; gap:6px;">
                @csrf
                <input type="file" name="file" required style="padding:4px;">
                <button type="submit" style="background:#2563eb; color:#fff; border:none; border-radius:6px; padding:7px 14px;">Import</button>
            </form>
            <a href="{{ route('kepala_desa.penduduk.format') }}" style="background:#f59e0b; color:#fff; padding:7px 14px; border-radius:6px; text-decoration:none;">Download Template</a>
            <a href="{{ route('kepala_desa.penduduk.export') }}" style="background:#22c55e; color:#fff; border:none; border-radius:6px; padding:7px 14px; text-decoration:none;">Export</a>
            <a href="{{ route('kepala_desa.penduduk.cetak') }}" target="_blank" style="background:#2563eb; color:#fff; padding:7px 14px; border-radius:6px; text-decoration:none;">Cetak PDF</a>
            <a href="{{ route('kepala_desa.penduduk.create') }}" style="background:#22c55e; color:#fff; border:none; border-radius:6px; padding:10px 18px; font-size:1rem; cursor:pointer; text-decoration:none;">Tambah Data</a>
        </div>
    </div>
    <table style="width:100%; border-collapse:collapse; margin-top:18px;">
        <thead>
            <tr style="background:#f4f6fa; color:#888fa6;">
                <th>No</th>
                <th>NIK</th>
                <th>Nama</th>
                <th>Jenis Kelamin</th>
                <th>Umur</th>
                <th>RT/RW</th>
                <th>Tanggungan</th>
                <th>Penghasilan</th>
                <th>Kondisi Rumah</th>
                <th>Status Kepemilikan Rumah</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($penduduks as $item)
                <tr>
                    <td>{{ $loop->iteration + ($penduduks->currentPage() - 1) * $penduduks->perPage() }}</td>
                    <td>{{ $item->nik }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->jenis_kelamin }}</td>
                    <td>{{ $item->umur }}</td>
                    <td>{{ $item->rt_rw }}</td>
                    <td>{{ $item->tanggungan }}</td>
                    <td>{{ $item->penghasilan }}</td>
                    <td>{{ $item->kondisi_rumah }}</td>
                    <td>{{ $item->status_kepemilikan_rumah }}</td>
                    <td>
                        <a href="{{ route('kepala_desa.penduduk.edit', $item->id) }}" style="background:#2563eb; color:#fff; border:none; border-radius:4px; padding:6px 14px; margin-right:6px; cursor:pointer; text-decoration:none;">Edit</a>
                        <form action="{{ route('kepala_desa.penduduk.destroy', $item->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background:#ef4444; color:#fff; border:none; border-radius:4px; padding:6px 14px; cursor:pointer;" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" style="padding:10px; text-align:center; color:#888fa6;">Tidak ada data penduduk.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top:18px;">
        {{ $penduduks->appends(request()->query())->links('pagination::simple-tailwind') }}
    </div>
</div>
@endsection
