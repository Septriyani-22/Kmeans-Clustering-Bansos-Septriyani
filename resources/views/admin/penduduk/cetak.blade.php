<!DOCTYPE html>
<html>
<head>
    <title>Data Penduduk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <h1>Data Penduduk</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIK</th>
                <th>Nama</th>
                <th>Tahun</th>
                <th>Jenis Kelamin</th>
                <th>Usia</th>
                <th>RT</th>
                <th>Tanggungan</th>
                <th>Penghasilan</th>
                <th>Kondisi Rumah</th>
                <th>Status Kepemilikan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($penduduk as $p)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $p->nik }}</td>
                <td>{{ $p->nama }}</td>
                <td>{{ $p->tahun }}</td>
                <td>{{ $p->jenis_kelamin }}</td>
                <td>{{ $p->usia }}</td>
                <td>{{ $p->rt }}</td>
                <td>{{ $p->tanggungan }}</td>
                <td class="text-right">Rp {{ number_format($p->penghasilan, 0, ',', '.') }}</td>
                <td>{{ $p->kondisi_rumah }}</td>
                <td>{{ $p->status_kepemilikan }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="11" style="text-align: center;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
