@extends('layouts.kepala_desa')
@section('title', 'Data Hasil')
@section('content')

    @if (session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif

    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 1rem;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 0.5rem;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background-color: #f3f4f6;
        }

        button,
        a.export-btn {
            display: inline-block;
            margin-top: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
        }

        button {
            background-color: #2563eb;
            color: white;
            border: none;
            cursor: pointer;
        }

        a.export-btn {
            background-color: #22c55e;
            color: white;
            margin-left: 10px;
        }

        .badge-success {
            background: #22c55e;
            color: #fff;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 0.95em;
        }

        .badge-danger {
            background: #ef4444;
            color: #fff;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 0.95em;
        }

        select {
            padding: 0.3rem 0.6rem;
            border-radius: 4px;
            border: 1px solid #ccc;
            margin-right: 0.5rem;
        }
    </style>

    <h1>Data Hasil K-Means</h1>
    <form method="GET" action="{{ route('kepala_desa.datahasil.index') }}">
            <select name="tahun">
                <option value="">-- Pilih Tahun --</option>
                @foreach ($tahunList as $tahun)
                    <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                @endforeach
            </select>
            <select name="periode">
                <option value="">-- Pilih Periode --</option>
                @foreach ($periodeList as $periode)
                    <option value="{{ $periode }}" {{ request('periode') == $periode ? 'selected' : '' }}>{{ $periode }}</option>
                @endforeach
            </select>
            <button type="submit">Tampilkan Hasil</button>
            <a href="{{ route('kepala_desa.datahasil.export', ['tahun' => request('tahun'), 'periode' => request('periode')]) }}"
            class="export-btn">Export Excel</a>

        </form>


        @if (isset($proses))
        <form method="GET"
            action="{{ route('kepala_desa.datahasil.hasil', ['tahun' => request('tahun'), 'periode' => request('periode')]) }}">
            <button type="submit" style="background:#22c55e;">Hasil Pengelompokan Penduduk</button>
        </form>
        <h3>Iterasi Perhitungan Ke {{ $proses['iterasi'] ?? 1 }}</h3>
        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>Centroid Awal</th>
                        @foreach ($proses['centroid_awal'] as $c)
                            <td>{{ implode(', ', $c) }}</td>
                        @endforeach
                    </tr>
                </thead>
            </table>
            <!-- Tambahkan tabel iterasi, jarak, cluster jika ada di $proses -->
        </div>
    @endif

    @if (isset($hasil_akhir))
        <h3>Data Hasil KMEANS</h3>
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Periode</th>
                    <th>Hasil</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($hasil_akhir as $row)
                    <tr>
                        <td>{{ $row['nama'] }}</td>
                        <td>{{ $row['periode'] }}</td>
                        <td>
                            @if ($row['hasil'] == 'Mendapatkan Bantuan')
                                <span class="badge-success">Mendapatkan Bantuan</span>
                            @else
                                <span class="badge-danger">Tidak Mendapatkan Bantuan</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Tabel default jika tidak ada proses/hasil_akhir -->
    @if (!isset($proses) && !isset($hasil_akhir))
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Memiliki Kartu Perlindungan Sosial (KPS)</th>
                    <th>Status Perkawinan</th>
                    <th>Umur</th>
                    <th>Jumlah Tanggungan Keluarga</th>
                    <th>Pekerjaan</th>
                    <th>Penghasilan</th>
                    <th>Status Kepemilikan Rumah</th>
                    <th>Luas Bangunan</th>
                    <th>Kondisi Rumah</th>
                    <th>Jaringan Listrik</th>
                    <th>Sumber Air</th>
                    <th>Kepemilikan Kendaraan</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @if ($hasil->count() > 0)
                    @foreach ($hasil as $row)
                        <tr>
                            <td>{{ $row->nama ?? '-' }}</td>
                            <td>{{ $row->kps ?? '-' }}</td>
                            <td>{{ $row->status_perkawinan ?? '-' }}</td>
                            <td>{{ $row->umur ?? '-' }}</td>
                            <td>{{ $row->jumlah_tanggungan ?? '-' }}</td>
                            <td>{{ $row->pekerjaan ?? '-' }}</td>
                            <td>{{ $row->penghasilan ?? '-' }}</td>
                            <td>{{ $row->status_kepemilikan_rumah ?? '-' }}</td>
                            <td>{{ $row->luas_bangunan ?? '-' }}</td>
                            <td>{{ $row->kondisi_rumah ?? '-' }}</td>
                            <td>{{ $row->jaringan_listrik ?? '-' }}</td>
                            <td>{{ $row->sumber_air ?? '-' }}</td>
                            <td>{{ $row->kepemilikan_kendaraan ?? '-' }}</td>
                            <td>{{ $row->total ?? '-' }}</td>
                            <td>{{ $row->status ?? '-' }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="15" style="text-align:center;">Data tidak ditemukan</td>
                    </tr>
                @endif
            </tbody>
        </table>

        @if(isset($hasil_akhir))
            <h3>Data Hasil KMEANS</h3>
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Periode</th>
                        <th>Hasil</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($hasil_akhir as $row)
                    <tr>
                        <td>{{ $row['nama'] }}</td>
                        <td>{{ $row['periode'] }}</td>
                        <td>
                            @if($row['hasil'] == 'Mendapatkan Bantuan')
                                <span class="badge-success">Mendapatkan Bantuan</span>
                            @else
                                <span class="badge-danger">Tidak Mendapatkan Bantuan</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

    </form>

    <div style="margin-top: 1rem;">
        {{ $hasil->appends(request()->query())->links() }}
    </div>
    @endif

@endsection