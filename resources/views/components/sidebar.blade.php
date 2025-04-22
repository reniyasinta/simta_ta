<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('dosen.dashboard') }}">Stisla</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('dosen.dashboard') }}">St</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="nav-item dropdown {{ ($type_menu ?? '') === 'dashboard' ? 'active' : '' }}">
                <a href="{{ route('dosen.dashboard') }}" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
            </li>
            <li class="menu-header">Jadwal</li>
            <li class="{{ Request::is('jadwal') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('jadwal') }}"><i class="far fa-calendar-alt"></i> <span>Jadwal Mengajar</span></a>
            </li>
            <li class="menu-header">Pengumuman</li>
            <li class="{{ Request::is('pengumuman') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('pengumuman') }}"><i class="fas fa-bullhorn"></i> <span>Pengumuman</span></a>
            </li>
            <li class="menu-header">Profil</li>
            <li class="{{ Request::is('profil') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('profil') }}"><i class="fas fa-user"></i> <span>Profil Dosen</span></a>
            </li>
        </ul>

    </aside>
</div>
