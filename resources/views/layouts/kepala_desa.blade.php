<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title', 'BANSOS KMEANS Kepala Desa')</title>
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet" />
  <style>
    body {
      margin: 0;
      font-family: 'Roboto', Arial, sans-serif;
      background: #f4f6fa;
    }
    .container {
      display: flex;
      min-height: 100vh;
    }
    .sidebar {
      width: 250px;
      background: linear-gradient(180deg, #3b82f6 0%, #2563eb 100%);
      color: #fff;
      display: flex;
      flex-direction: column;
      padding-top: 30px;
    }
    .sidebar h2 {
      text-align: center;
      font-size: 1.5rem;
      font-weight: 700;
      margin-bottom: 20px;
      letter-spacing: 1px;
    }
    .sidebar .section-label {
      font-size: 0.75rem;
      opacity: 0.7;
      padding: 0 30px;
      margin: 10px 0 5px;
    }
    .sidebar ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    .sidebar ul li {
      padding: 12px 30px;
      font-size: 1rem;
      cursor: pointer;
      transition: background 0.2s;
    }
    .sidebar ul li:hover,
    .sidebar ul li.active {
      background: rgba(255, 255, 255, 0.1);
    }
    .sidebar ul li a {
      color: inherit;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .sidebar .collapse-btn {
      margin-top: auto;
      padding: 20px 0;
      text-align: center;
      font-size: 1.2rem;
      opacity: 0.6;
    }
    .main-content {
      flex: 1;
      background: #f4f6fa;
      display: flex;
      flex-direction: column;
    }
    .topbar {
      height: 60px;
      background: #fff;
      display: flex;
      align-items: center;
      justify-content: flex-end;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
      padding: 0 32px;
    }
    .topbar .user {
      display: flex;
      align-items: center;
      gap: 15px;
    }
    .topbar .user img {
      width: 36px;
      height: 36px;
      border-radius: 50%;
    }
    .content-area {
      padding: 32px 40px;
    }

    @media (max-width: 600px) {
      .sidebar {
        width: 100px;
        padding-top: 10px;
      }
      .sidebar h2 {
        font-size: 1rem;
        margin-bottom: 10px;
      }
      .sidebar ul li {
        padding: 10px;
        font-size: 0.85rem;
      }
      .content-area {
        padding: 12px 4px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <nav class="sidebar">
      <h2>🧊 BANSOS<span style="font-weight: 400;">KMEANS</span></h2>

      <ul>
        <li class="@if(request()->is('kepala_desa/dashboard')) active @endif">
          <a href="/kepala_desa/dashboard">👤 Dashboard</a>
        </li>
        <li class="@if(request()->is('kepala_desa/users')) active @endif">
          <a href="/kepala_desa/users">👥 Users</a>
        </li>
        <li class="@if(request()->is('kepala_desa/kriteria')) active @endif">
          <a href="/kepala_desa/kriteria">🏷️ Kriteria</a>
        </li>
        <li class="@if(request()->is('kepala_desa/penduduk')) active @endif">
          <a href="/kepala_desa/penduduk">📋 Data Penduduk</a>
        </li>
        <li class="@if(request()->is('kepala_desa/centroid')) active @endif">
          <a href="/kepala_desa/centroid">📍 Centroid</a>
        </li>
        <li class="@if(request()->is('kepala_desa/clustering')) active @endif">
          <a href="/kepala_desa/clustering">🔄 Clustering</a>
        </li>
        <li class="@if(request()->is('kepala_desa/datahasil')) active @endif">
          <a href="/kepala_desa/datahasil">📊 Data Hasil</a>
        </li>
        <li class="@if(request()->is('kepala_desa/laporanhasil')) active @endif">
          <a href="/kepala_desa/laporanhasil">🧾 Laporan Hasil</a>
        </li>
      </ul>

      <div class="collapse-btn">⬅️</div>
    </nav>

    <div class="main-content">
      <div class="topbar">
        <div class="user">
          <span>MENU</span>
          <img src="https://ui-avatars.com/api/?name=User" alt="User">
          <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" style="background: none; border: none; color: #2563eb; cursor: pointer;">
              Logout
            </button>
          </form>
        </div>
      </div>

      <div class="content-area">
        @yield('content')
      </div>
    </div>
  </div>
  <footer style="text-align: center; padding: 20px; background: #f8fafc; border-top: 1px solid #e2e8f0; margin-top: 40px;">
    <p style="color: #64748b; font-size: 0.9rem; margin: 0;">
      Copyright © SISTEM OPTIMALISASI PENYALURAN BANTUAN SOSIAL MENGGUNAKAN K-MEANS CLUSTERING BERDASARKAN SEGMENTASI DATA SOSIAL DAN EKONOMI (STUDI KASUS DESA TANJUNG SERANG KECAMATAN KAYUAGUNG)
    </p>
  </footer>
</body>
</html>