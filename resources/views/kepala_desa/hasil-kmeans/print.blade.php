<!DOCTYPE html>
<html>
<head>
    <title>Print Hasil K-Means</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .summary {
            margin-top: 20px;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Hasil K-Means Clustering</h2>
        <p>Tanggal: {{ date('d/m/Y') }}</p>
    </div>

    <div class="summary">
        <h3>Ringkasan</h3>
        <p>Total Data: {{ $totalData }}</p>
        <p>Layak Bantuan (C1): {{ $layakBantuan }}</p>
        <p>Tidak Layak (C2): {{ $tidakLayak }}</p>
        <p>Prioritas Sedang (C3): {{ $prioritasSedang }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Usia</th>
                <th>Jumlah Tanggungan</th>
                <th>Kondisi Rumah</th>
                <th>Status Kepemilikan</th>
                <th>Penghasilan</th>
                <th>Cluster</th>
                <th>Kelayakan</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($hasilKmeans as $hasil)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $hasil->nama_penduduk }}</td>
                <td>{{ $hasil->usia }}</td>
                <td>{{ $hasil->jumlah_tanggungan }}</td>
                <td>{{ $hasil->kondisi_rumah }}</td>
                <td>{{ $hasil->status_kepemilikan }}</td>
                <td>{{ number_format($hasil->jumlah_penghasilan, 0, ',', '.') }}</td>
                <td>{{ $hasil->cluster }}</td>
                <td>{{ $hasil->kelayakan }}</td>
                <td>{{ $hasil->keterangan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="no-print" style="margin-top: 20px;">
        <button onclick="window.print()">Print</button>
        <button onclick="window.history.back()">Kembali</button>
    </div>
</body>
</html> 