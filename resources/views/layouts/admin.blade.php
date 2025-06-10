<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title', 'BANSOS KMEANS Admin')</title>
  
  <!-- AdminLTE CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=swap" rel="stylesheet">
  
  <style>
    .info-box {
      min-height: 90px;
    }
    .info-box-icon {
      height: 90px;
      width: 90px;
      font-size: 1.875rem;
      line-height: 90px;
    }
    .info-box-content {
      padding: 5px 10px;
      margin-left: 90px;
    }
    .info-box-number {
      font-size: 1.5rem;
      font-weight: 700;
    }
    .card {
      box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
      margin-bottom: 1rem;
    }
    .badge {
      font-size: 0.875rem;
      font-weight: 600;
      padding: 0.5em 0.75em;
    }
    .badge-success {
      background-color: #28a745;
    }
    .badge-danger {
      background-color: #dc3545;
    }
    .table th {
      font-weight: 600;
      background-color: #f8f9fa;
    }
  </style>
  
  @stack('styles')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          <i class="fas fa-sign-out-alt"></i> Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
          @csrf
        </form>
      </li>
    </ul>
  </nav>

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
      <span class="brand-text font-weight-light">BANSOS KMEANS</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
          <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('admin.penduduk.index') }}" class="nav-link {{ request()->routeIs('admin.penduduk.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-users"></i>
              <p>Penduduk</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('admin.kriteria.index') }}" class="nav-link {{ request()->routeIs('admin.kriteria.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-list"></i>
              <p>Kriteria</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('admin.centroid.index') }}" class="nav-link {{ request()->routeIs('admin.centroid.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>Centroid</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('admin.clustering.index') }}" class="nav-link {{ request()->routeIs('admin.clustering.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-cogs"></i>
              <p>Clustering</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('admin.hasil-kmeans.index') }}" class="nav-link {{ request()->routeIs('admin.hasil-kmeans.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-chart-bar"></i>
              <p>Hasil K-Means</p>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </aside>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">@yield('title')</h1>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        @yield('content')
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; {{ date('Y') }} <a href="#">BANSOS KMEANS</a>.</strong> All rights reserved.
  </footer>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

@stack('scripts')
</body>
</html>
