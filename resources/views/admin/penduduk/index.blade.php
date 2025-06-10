@extends('layouts.admin')

@section('title', 'Data Penduduk - BANSOS KMEANS')

@section('content')
<div style="padding: 24px; min-height: 100vh;">
    <div style="background:#fff; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,0.07); padding:24px;">
        <h1 style="font-size:2rem; color:#888fa6; font-weight:400; margin-bottom:18px;">Data Penduduk</h1>
        
        <!-- Search and Filter Section -->
        <div style="display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; gap:12px; margin-bottom:18px; background:#f8fafc; padding:16px; border-radius:8px;">
            <form method="GET" action="" style="display:flex; gap:8px; flex-wrap:wrap; align-items:center; flex:1;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIK atau Nama..." style="padding:8px 12px; border:1px solid #d1d5db; border-radius:6px; font-size:1rem; flex:1; min-width:200px;">
                <select name="jenis_kelamin" style="padding:8px 12px; border:1px solid #d1d5db; border-radius:6px; font-size:1rem; min-width:150px;">
                    <option value="">Semua Jenis Kelamin</option>
                    <option value="L" {{ request('jenis_kelamin')=='L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ request('jenis_kelamin')=='P' ? 'selected' : '' }}>Perempuan</option>
                </select>
                <input type="number" name="rt" value="{{ request('rt') }}" placeholder="RT" style="padding:8px 12px; border:1px solid #d1d5db; border-radius:6px; font-size:1rem; width:100px;">
                <button type="submit" style="background:#2563eb; color:#fff; border:none; border-radius:6px; padding:8px 16px; font-size:1rem; white-space:nowrap;">Cari/Filter</button>
            </form>
            <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
                <form method="POST" action="{{ route('admin.penduduk.import') }}" enctype="multipart/form-data" style="display:inline-flex; align-items:center; gap:6px;">
                    @csrf
                    <input type="file" name="file" required style="padding:4px;">
                    <button type="submit" style="background:#2563eb; color:#fff; border:none; border-radius:6px; padding:8px 14px;">Import</button>
                </form>
                <a href="{{ route('admin.penduduk.format') }}" style="background:#f59e0b; color:#fff; padding:8px 14px; border-radius:6px; text-decoration:none; white-space:nowrap;">Download Template</a>
                <a href="{{ route('admin.penduduk.export') }}" style="background:#22c55e; color:#fff; border:none; border-radius:6px; padding:8px 14px; text-decoration:none; white-space:nowrap;">Export</a>
                <a href="{{ route('admin.penduduk.cetak') }}" target="_blank" style="background:#2563eb; color:#fff; padding:8px 14px; border-radius:6px; text-decoration:none; white-space:nowrap;">Cetak PDF</a>
                <a href="{{ route('admin.penduduk.create') }}" style="background:#22c55e; color:#fff; border:none; border-radius:6px; padding:10px 18px; font-size:1rem; cursor:pointer; text-decoration:none; white-space:nowrap;">Tambah Data</a>
            </div>
        </div>

        <!-- Table Section -->
        <div style="overflow-x:auto; background:#fff; border-radius:8px; border:1px solid #e5e7eb;">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#f4f6fa; color:#888fa6;">
                        <th style="padding:12px; text-align:left; white-space:nowrap;">No</th>
                        <th style="padding:12px; text-align:left; white-space:nowrap;">NIK</th>
                        <th style="padding:12px; text-align:left; white-space:nowrap;">Nama</th>
                        <th style="padding:12px; text-align:left; white-space:nowrap;">Tahun</th>
                        <th style="padding:12px; text-align:left; white-space:nowrap;">Jenis Kelamin</th>
                        <th style="padding:12px; text-align:left; white-space:nowrap;">Usia</th>
                        <th style="padding:12px; text-align:left; white-space:nowrap;">RT</th>
                        <th style="padding:12px; text-align:left; white-space:nowrap;">Tanggungan</th>
                        <th style="padding:12px; text-align:left; white-space:nowrap;">Kondisi Rumah</th>
                        <th style="padding:12px; text-align:left; white-space:nowrap;">Status Kepemilikan</th>
                        <th style="padding:12px; text-align:left; white-space:nowrap;">Penghasilan</th>
                        <th style="padding:12px; text-align:left; white-space:nowrap;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($penduduks as $item)
                        <tr style="border-bottom:1px solid #e5e7eb; hover:background:#f8fafc;">
                            <td style="padding:12px; white-space:nowrap;">{{ $loop->iteration + ($penduduks->currentPage() - 1) * $penduduks->perPage() }}</td>
                            <td style="padding:12px; white-space:nowrap;">{{ $item->nik }}</td>
                            <td style="padding:12px; white-space:nowrap;">{{ $item->nama }}</td>
                            <td style="padding:12px; white-space:nowrap;">{{ $item->tahun }}</td>
                            <td style="padding:12px; white-space:nowrap;">{{ $item->jenis_kelamin_text }}</td>
                            <td style="padding:12px; white-space:nowrap;">{{ $item->usia }}</td>
                            <td style="padding:12px; white-space:nowrap;">{{ $item->rt }}</td>
                            <td style="padding:12px; white-space:nowrap;">{{ $item->tanggungan }}</td>
                            <td style="padding:12px; white-space:nowrap;">{{ ucfirst($item->kondisi_rumah) }}</td>
                            <td style="padding:12px; white-space:nowrap;">{{ ucfirst($item->status_kepemilikan) }}</td>
                            <td style="padding:12px; white-space:nowrap;">Rp {{ number_format($item->penghasilan, 0, ',', '.') }}</td>
                            <td style="padding:12px; white-space:nowrap;">
                                <div style="display:flex; gap:6px;">
                                    <a href="{{ route('admin.penduduk.edit', $item->id) }}" style="background:#2563eb; color:#fff; border:none; border-radius:4px; padding:6px 14px; cursor:pointer; text-decoration:none;">Edit</a>
                                    <form action="{{ route('admin.penduduk.destroy', $item->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background:#ef4444; color:#fff; border:none; border-radius:4px; padding:6px 14px; cursor:pointer;" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" style="padding:24px; text-align:center; color:#888fa6;">Tidak ada data penduduk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div style="margin-top:18px; display:flex; justify-content:center;">
            {{ $penduduks->appends(request()->query())->links('pagination::simple-tailwind') }}
        </div>
    </div>
</div>
@endsection
