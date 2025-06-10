<!DOCTYPE html>
<html>
<head>
    <title>Cetak Data Penduduk</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; font-size: 13px; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body onload="window.print()">
    <h2 style="text-align:center;">Data Penduduk</h2>
    <table>
        <thead>
            <tr>
                <th>NIK</th>
                <th>Nama</th>
                <th>Tahun</th>
                <th>Jenis Kelamin</th>
                <th>Usia</th>
                <th>RT</th>
                <th>Tanggungan</th>
                <th>Kondisi Rumah</th>
                <th>Status Kepemilikan</th>
                <th>Penghasilan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penduduks as $p)
            <tr>
                <td>{{ $p->nik }}</td>
                <td>{{ $p->nama }}</td>
                <td>{{ $p->tahun }}</td>
                <td>{{ $p->jenis_kelamin_text }}</td>
                <td>{{ $p->usia }}</td>
                <td>{{ $p->rt }}</td>
                <td>{{ $p->tanggungan }}</td>
                <td>{{ ucfirst($p->kondisi_rumah) }}</td>
                <td>{{ ucfirst($p->status_kepemilikan) }}</td>
                <td>Rp {{ number_format($p->penghasilan, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
