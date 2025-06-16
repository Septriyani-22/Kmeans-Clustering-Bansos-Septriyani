<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi - Sistem Bantuan Sosial</title>
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
        }

        .card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            overflow: hidden;
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

        .info-section {
            margin-bottom: 2rem;
        }

        .info-section h2 {
            color: #2d3748;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .info-section p {
            color: #4a5568;
            line-height: 1.8;
            margin-bottom: 1rem;
        }

        .criteria-list {
            list-style: none;
            counter-reset: criteria-counter;
        }

        .criteria-list li {
            position: relative;
            padding: 1rem 1rem 1rem 3rem;
            margin-bottom: 1rem;
            background: #f8fafc;
            border-radius: 8px;
            border-left: 4px solid #3b82f6;
        }

        .criteria-list li::before {
            counter-increment: criteria-counter;
            content: counter(criteria-counter);
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            width: 24px;
            height: 24px;
            background: #3b82f6;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .criteria-list li h3 {
            color: #2d3748;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .criteria-list li p {
            color: #4a5568;
            font-size: 0.95rem;
            margin: 0;
        }

        .weight-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background: #e2e8f0;
            color: #4a5568;
            border-radius: 20px;
            font-size: 0.9rem;
            margin-left: 0.5rem;
        }

        .process-section {
            background: #f8fafc;
            padding: 1.5rem;
            border-radius: 8px;
            margin-top: 2rem;
        }

        .process-section h3 {
            color: #2d3748;
            font-size: 1.2rem;
            margin-bottom: 1rem;
        }

        .process-steps {
            list-style: none;
            counter-reset: step-counter;
        }

        .process-steps li {
            position: relative;
            padding: 1rem 1rem 1rem 3rem;
            margin-bottom: 1rem;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .process-steps li::before {
            counter-increment: step-counter;
            content: counter(step-counter);
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            width: 24px;
            height: 24px;
            background: #3b82f6;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .process-steps li:last-child {
            margin-bottom: 0;
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
                    <h1>Informasi Program Bantuan Sosial</h1>
                </div>
                <div class="card-body">
                    <div class="info-section">
                        <h2>Tentang Program</h2>
                        <p>
                            Program Bantuan Sosial Beras (Raskin) adalah program pemerintah yang bertujuan untuk membantu masyarakat miskin 
                            dalam memenuhi kebutuhan pangan pokok mereka. Program ini menggunakan metode K-Means Clustering untuk 
                            mengelompokkan penerima bantuan berdasarkan kriteria sosial dan ekonomi.
                        </p>
                    </div>

                    <div class="info-section">
                        <h2>Kriteria Penilaian</h2>
                        <p>Berikut adalah kriteria yang digunakan dalam penilaian kelayakan penerima bantuan:</p>
                        <ul class="criteria-list">
                            <li>
                                <h3>Penghasilan <span class="weight-badge">Bobot: 30%</span></h3>
                                <p>Penghasilan total keluarga per bulan. Semakin rendah penghasilan, semakin tinggi skor yang diberikan.</p>
                            </li>
                            <li>
                                <h3>Tanggungan <span class="weight-badge">Bobot: 25%</span></h3>
                                <p>Jumlah anggota keluarga yang menjadi tanggungan. Semakin banyak tanggungan, semakin tinggi skor yang diberikan.</p>
                            </li>
                            <li>
                                <h3>Kondisi Rumah <span class="weight-badge">Bobot: 20%</span></h3>
                                <p>Kondisi fisik rumah tempat tinggal. Kondisi yang kurang baik akan mendapatkan skor lebih tinggi.</p>
                            </li>
                            <li>
                                <h3>Status Kepemilikan Rumah <span class="weight-badge">Bobot: 15%</span></h3>
                                <p>Status kepemilikan rumah tempat tinggal. Tidak memiliki rumah akan mendapatkan skor lebih tinggi.</p>
                            </li>
                            <li>
                                <h3>Usia <span class="weight-badge">Bobot: 10%</span></h3>
                                <p>Usia kepala keluarga. Usia lanjut akan mendapatkan skor lebih tinggi.</p>
                            </li>
                        </ul>
                    </div>

                    <div class="process-section">
                        <h3>Proses Pengelompokan</h3>
                        <p>Metode K-Means Clustering digunakan untuk mengelompokkan calon penerima bantuan menjadi beberapa cluster berdasarkan kriteria di atas:</p>
                        <ul class="process-steps">
                            <li>Pengumpulan data calon penerima bantuan</li>
                            <li>Normalisasi data berdasarkan kriteria yang ditetapkan</li>
                            <li>Penentuan centroid awal untuk setiap cluster</li>
                            <li>Pengelompokan data berdasarkan jarak ke centroid terdekat</li>
                            <li>Perhitungan ulang centroid berdasarkan data dalam cluster</li>
                            <li>Pengulangan proses hingga konvergen</li>
                            <li>Penentuan status kelayakan berdasarkan cluster</li>
                        </ul>
                    </div>

                    <div class="info-section">
                        <h2>Status Kelayakan</h2>
                        <p>
                            Berdasarkan hasil pengelompokan, calon penerima bantuan akan dikategorikan sebagai:
                        </p>
                        <ul class="criteria-list">
                            <li>
                                <h3>C1 (Cluster Membutuhkan)</h3>
                                <p>Kelompok penerima yang membutuhkan bantuan sosial berdasarkan kriteria yang ditetapkan.</p>
                            </li>
                            <li>
                                <h3>C2 (Cluster Tidak Membutuhkan)</h3>
                                <p>Kelompok penerima yang tidak membutuhkan bantuan sosial berdasarkan kriteria yang ditetapkan.</p>
                            </li>
                            <li>
                                <h3>C3 (Prioritas Sedang)</h3>
                                <p>Kelompok penerima dengan prioritas sedang untuk menerima bantuan sosial.</p>
                            </li>
                        </ul>
                    </div>
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
