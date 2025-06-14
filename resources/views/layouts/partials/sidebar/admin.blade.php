<li class="nav-item">
    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>Dashboard</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-users"></i>
        <p>Users</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('admin.kriteria.index') }}" class="nav-link {{ request()->routeIs('admin.kriteria.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-list"></i>
        <p>Kriteria</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('admin.penduduk.index') }}" class="nav-link {{ request()->routeIs('admin.penduduk.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-user-friends"></i>
        <p>Penduduk</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('admin.clustering.index') }}" class="nav-link {{ request()->routeIs('admin.clustering.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-project-diagram"></i>
        <p>Clustering</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('admin.centroid.index') }}" class="nav-link {{ request()->routeIs('admin.centroid.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-bullseye"></i>
        <p>Centroid</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('admin.hasil-kmeans.index') }}" class="nav-link {{ request()->routeIs('admin.hasil-kmeans.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-chart-pie"></i>
        <p>Hasil K-Means</p>
    </a>
</li> 