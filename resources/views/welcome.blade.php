<!DOCTYPE html>
<html lang="id">

    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Utama - Sistem Bantuan Sosial</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Roboto', Arial, sans-serif;
            background: #f4f6fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .container {
            display: flex;
            flex: 1;
        }

        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, #3b82f6 0%, #2563eb 100%);
            color: #fff;
            padding: 2rem 0;
            position: fixed;
            height: 100vh;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }

        .sidebar h2 {
            text-align: center;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 2rem;
            padding: 0 1rem;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar ul li {
            padding: 0.8rem 1.5rem;
            transition: all 0.3s ease;
        }

        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            font-size: 1rem;
        }

        .sidebar ul li a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .sidebar ul li:hover {
            background: rgba(255,255,255,0.1);
        }

        .sidebar ul li.active {
            background: rgba(255,255,255,0.2);
            border-left: 4px solid #fff;
        }

        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            overflow: hidden;
            width: 100%;
            max-width: 1000px;
        }

        .card-header {
            padding: 1.5rem;
            border-bottom: 1px solid #eee;
            text-align: center;
            background: linear-gradient(120deg, #2980b9, #8e44ad);
            color: #fff;
        }

        .card-body {
            padding: 1.5rem;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-section img {
            width: 120px;
            height: auto;
            margin-bottom: 1rem;
        }

        .welcome-text {
            text-align: center;
            color: #fff;
            line-height: 1.8;
        }

        .welcome-text h1 {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .welcome-text p {
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }

        .search-section {
            text-align: center;
            margin-top: 2rem;
        }

        .search-section h2 {
            font-size: 1.5rem;
            color: #2d3748;
            margin-bottom: 1.5rem;
        }

        .search-form {
            display: flex;
            gap: 1rem;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-input {
            flex: 1;
            min-width: 200px;
            padding: 0.8rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .search-button {
            padding: 0.8rem 2rem;
            background: #3b82f6;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .search-button:hover {
            background: #2563eb;
        }

        .result-section {
            margin-top: 2rem;
            width: 100%;
        }

        .result-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1.5rem;
            margin-bottom: 1rem;
        }

        .result-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }

        .result-title {
            font-size: 1.2rem;
            color: #2d3748;
            font-weight: 600;
        }

        .result-status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .status-layak {
            background: #c6f6d5;
            color: #2f855a;
        }

        .status-tidak-layak {
            background: #fed7d7;
            color: #c53030;
        }

        .result-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .detail-item {
            padding: 0.5rem;
        }

        .detail-label {
            font-size: 0.9rem;
            color: #718096;
            margin-bottom: 0.25rem;
        }

        .detail-value {
            font-size: 1rem;
            color: #2d3748;
            font-weight: 500;
        }

        .no-result {
            text-align: center;
            padding: 2rem;
            color: #718096;
        }

        footer {
            background: #e2e8f0;
            padding: 1rem;
            text-align: center;
            color: #4a5568;
            font-size: 0.9rem;
            margin-top: auto;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
                padding: 1rem 0;
            }

            .sidebar h2 {
                font-size: 0;
            }

            .sidebar ul li a span {
                display: none;
            }

            .sidebar ul li a i {
                margin: 0;
                font-size: 1.2rem;
            }

            .main-content {
                margin-left: 70px;
                padding: 1rem;
            }

            .card {
                margin: 0 1rem;
            }

            .search-form {
                flex-direction: column;
            }

            .search-input {
                width: 100%;
            }

            .search-button {
                width: 100%;
            }

            .result-details {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <nav class="sidebar">
            <h2>BANSOS<span style="font-weight:400;">KMEANS</span></h2>
            <ul>
                <li class="{{ request()->is('/') ? 'active' : '' }}">
                    <a href="/"><i class="fas fa-home"></i><span>Menu Utama</span></a>
                </li>
                <li class="{{ request()->is('informasi') ? 'active' : '' }}">
                    <a href="/informasi"><i class="fas fa-info-circle"></i><span>Informasi</span></a>
                </li>
                <li class="{{ request()->is('login') ? 'active' : '' }}">
                    <a href="/login"><i class="fas fa-sign-in-alt"></i><span>Login</span></a>
                </li>
            </ul>
        </nav>

        <main class="main-content">
            <div class="card">
                <div class="card-header">
                    <div class="logo-section">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width:100px; height:100px; border-radius:50%; background:none;">
                    </div>
                    <div class="welcome-text">
                        <h1>Selamat Datang</h1>
                        <p>
                            SISTEM OPTIMALISASI PENYALURAN BANTUAN SOSIAL<br>
                            MENGGUNAKAN K-MEANS CLUSTERING BERDASARKAN SEGMENTASI<br>
                            DATA SOSIAL DAN EKONOMI<br>
                            (STUDI KASUS DESA TANJUNG SERANG KECAMATAN KAYUAGUNG)
                        </p>
                    </div>
                </div>
                <div class="card-body">
                    <div class="search-section">
                        <h2>LIHAT INFORMASI PENERIMAAN BANTUAN BERAS</h2>
                        <form method="GET" action="{{ route('search.result') }}" class="search-form">
                            <input type="text" id="nik" name="nik" class="search-input" placeholder="Masukkan NIK" required>
                            <button type="submit" class="search-button">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </form>
                    </div>

                    @if(isset($result))
                    <div class="result-section">
                        <div class="result-card">
                            <div class="result-header">
                                <div class="result-title">
                                    {{ $result->penduduk->nama }} ({{ $result->penduduk->nik }})
                                </div>
                                <div class="result-status {{ $result->kelayakan === 'Layak' ? 'status-layak' : 'status-tidak-layak' }}">
                                    {{ $result->kelayakan }}
                                </div>
                            </div>
                            <div class="result-details">
                                <div class="detail-item">
                                    <div class="detail-label">Cluster</div>
                                    <div class="detail-value">{{ $result->cluster }}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Skor Kelayakan</div>
                                    <div class="detail-value">{{ number_format($result->skor_kelayakan, 2) }}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Penghasilan</div>
                                    <div class="detail-value">{{ $result->penduduk->penghasilan }}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Tanggungan</div>
                                    <div class="detail-value">{{ $result->penduduk->tanggungan }}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Kondisi Rumah</div>
                                    <div class="detail-value">{{ $result->penduduk->kondisi_rumah }}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Status Kepemilikan</div>
                                    <div class="detail-value">{{ $result->penduduk->status_kepemilikan_rumah }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @elseif(request()->has('nik'))
                    <div class="no-result">
                        <i class="fas fa-search fa-3x mb-3"></i>
                        <p>Data tidak ditemukan untuk NIK tersebut</p>
                    </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    <footer>
        Copyright © Pengelompokkan Penerima Bantuan Sosial Beras Untuk Masyarakat Miskin
        Di Desa Burum Kabupaten Tabalong Menggunakan Metode K-Means
    </footer>
</body>

</html>