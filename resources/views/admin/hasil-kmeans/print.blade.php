<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Hasil K-Means Clustering</title>
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
        .summary {
            margin-top: 20px;
        }
        .summary p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Hasil K-Means Clustering</h2>
        <p>Tanggal Cetak: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <div class="summary">
        <p>Total Data: {{ $totalData }}</p>
        <p>Cluster 1 (Membutuhkan): {{ $layakBantuan }}</p>
        <p>Cluster 2 (Tidak Membutuhkan): {{ $tidakLayak }}</p>
        <p>Cluster 3 (Prioritas Sedang): {{ $prioritasSedang }}</p>
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
                <th>Jumlah Penghasilan</th>
                <th>Cluster</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($hasilKmeans as $result)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $result->nama_penduduk }}</td>
                    <td>{{ $result->usia }}</td>
                    <td>{{ $result->jumlah_tanggungan }}</td>
                    <td>{{ $result->kondisi_rumah }}</td>
                    <td>{{ $result->status_kepemilikan }}</td>
                    <td>Rp {{ number_format($result->jumlah_penghasilan, 0, ',', '.') }}</td>
                    <td>{{ $result->cluster }}</td>
                    <td>{{ $result->keterangan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 