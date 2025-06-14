<li class="nav-item">
    <a href="{{ route('kepala_desa.dashboard') }}" class="nav-link {{ request()->routeIs('kepala_desa.dashboard') ? 'active' : '' }}">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>Dashboard</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('kepala_desa.penduduk.index') }}" class="nav-link {{ request()->routeIs('kepala_desa.penduduk.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-user-friends"></i>
        <p>Penduduk</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('kepala_desa.hasil-kmeans.index') }}" class="nav-link {{ request()->routeIs('kepala_desa.hasil-kmeans.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-chart-pie"></i>
        <p>Hasil K-Means</p>
    </a>
</li> 