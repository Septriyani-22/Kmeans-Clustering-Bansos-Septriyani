<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Penduduk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Data Penduduk</h2>
        <p>Tanggal Cetak: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIK</th>
                <th>Nama</th>
                <th>Tahun</th>
                <th>Jenis Kelamin</th>
                <th>Usia</th>
                <th>Rt</th>
                <th>Tanggungan</th>
                <th>Kondisi Rumah</th>
                <th>Status Kepemilikan</th>
                <th>Penghasilan</th>
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
                    <td>{{ $p->kondisi_rumah }}</td>
                    <td>{{ $p->status_kepemilikan }}</td>
                    <td>Rp {{ number_format($p->penghasilan, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center;">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh sistem.</p>
    </div>
</body>
</html> 