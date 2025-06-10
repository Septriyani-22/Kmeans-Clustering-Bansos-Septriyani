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
                <th>Jenis Kelamin</th>
                <th>Umur</th>
                <th>RT/RW</th>
                <th>Tanggungan</th>
                <th>Penghasilan</th>
                <th>Kondisi Rumah</th>
                <th>Status Kepemilikan Rumah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penduduks as $p)
            <tr>
                <td>{{ $p->nik }}</td>
                <td>{{ $p->nama }}</td>
                <td>{{ $p->jenis_kelamin }}</td>
                <td>{{ $p->umur }}</td>
                <td>{{ $p->rt_rw }}</td>
                <td>{{ $p->tanggungan }}</td>
                <td>{{ $p->penghasilan }}</td>
                <td>{{ $p->kondisi_rumah }}</td>
                <td>{{ $p->status_kepemilikan_rumah }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
