@extends('layouts.admin')
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
    <form method="GET" action="{{ route('admin.datahasil.index') }}">
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
            <a href="{{ route('admin.datahasil.export', ['tahun' => request('tahun'), 'periode' => request('periode')]) }}"
            class="export-btn">Export Excel</a>

        </form>


        @if (isset($proses))
        <form method="GET"
            action="{{ route('admin.datahasil.hasil', ['tahun' => request('tahun'), 'periode' => request('periode')]) }}">
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
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($hasil_akhir as $row)
                    <tr>
                        <td>{{ $row['nama'] }}</td>
                        <td>{{ $row['periode'] }}</td>
                        <td>
                            @if ($row['hasil'] == 'Layak')
                                <span class="badge-success">Layak</span>
                            @else
                                <span class="badge-danger">Tidak Layak</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Tabel default jika tidak ada proses/hasil_akhir -->
    @if (!isset($proses) && !isset($hasil_akhir))
        <h2>Data Hasil Clustering</h2>
        @if(session('success'))
            <div style="color:green;">{{ session('success') }}</div>
        @endif
        <table border="1" cellpadding="8">
            <tr>
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
                <th>Status</th>
            </tr>
            @foreach($hasil as $i => $row)
            <tr>
                <td>{{ $hasil->firstItem() + $i }}</td>
                <td>{{ $row->nik }}</td>
                <td>{{ $row->nama }}</td>
                <td>{{ $row->jenis_kelamin ?? '-' }}</td>
                <td>{{ $row->umur ?? '-' }}</td>
                <td>{{ $row->rt_rw ?? '-' }}</td>
                <td>{{ $row->tanggungan ?? '-' }}</td>
                <td>{{ $row->penghasilan ?? '-' }}</td>
                <td>{{ $row->kondisi_rumah ?? '-' }}</td>
                <td>{{ $row->status_kepemilikan_rumah ?? '-' }}</td>
                <td>
                    @if($row->hasil == 'Layak')
                        <span class="badge-success">Layak</span>
                    @else
                        <span class="badge-danger">Tidak Layak</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </table>
        <div style="margin-top:10px;">{{ $hasil->links() }}</div>
    @endif

@endsection