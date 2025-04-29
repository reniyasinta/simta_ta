<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ url('/') }}">SIMTA</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ url('/') }}">ST</a>
        </div>

        <!-- Shared Menu (All Roles) -->
        @php $role = auth()->user()->role; @endphp
        <!-- Admin -->
        @if($role === 'admin')
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="nav-item dropdown {{ ($type_menu ?? '') === 'dashboard' ? 'active' : '' }}">
                <a href="{{ route('dosen.dashboard') }}" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
                <li class="nav-item dropdown {{ ($type_menu ?? '') === 'persyaratan' ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-file-alt"></i> <span>Berkas Persyaratan</span></a>
                    <ul class="dropdown-menu">
                        <li class="{{ request()->routeIs('dosen.dashboard') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('dosen.dashboard') }}">Persyaratan Sempro</a>
                        </li>
                        <li class="{{ request()->routeIs('dosen.dashboard') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('dosen.dashboard') }}">Persyaratan Tugas Akhir</a>
                        </li>
                    </ul>
                </li>
            </li>
            <li class="menu-header">Jadwal</li>
            <li class="{{ Request::is('jadwal') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('jadwal') }}"><i class="far fa-calendar-alt"></i> <span>Jadwal Seminar</span></a>
                <a class="nav-link" href="{{ url('jadwal') }}"><i class="far fa-calendar-alt"></i> <span>Jadwal Sidang</span></a>
            </li>
            <li class="menu-header">Pengumuman</li>
            <li class="{{ Request::is('pengumuman') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('pengumuman') }}"><i class="fas fa-bullhorn"></i> <span>Pengumuman</span></a>
            </li>
            <li class="menu-header">Profil</li>
            <li class="{{ Request::is('profil') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('profil') }}"><i class="fas fa-user"></i> <span>Profil</span></a>
            </li>
        </ul>


        <!-- Panitia -->
        @elseif($role === 'panitia')
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="nav-item dropdown {{ ($type_menu ?? '') === 'dashboard' ? 'active' : '' }}">
                <a href="{{ route('dosen.dashboard') }}" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
            </li>
            <li class="menu-header">Jadwal</li>
            <li class="nav-item dropdown {{ Request::is('jadwal*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-calendar"></i> <span>Jadwal</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ request()->routeIs('dosen.dashboard') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('dosen.dashboard') }}">Jadwal Sempro</a>
                    </li>
                    <li class="{{ request()->routeIs('dosen.dashboard') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('dosen.dashboard') }}">Jadwal Sidang</a>
                    </li>
                    <li class="{{ request()->routeIs('dosen.dashboard') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('dosen.dashboard') }}">Jadwal Sosialisasi</a>
                    </li>
                </ul>
            </li>



            <li class="menu-header">Pengumuman</li>
            <li class="{{ Request::is('pengumuman') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('pengumuman') }}"><i class="fas fa-bullhorn"></i> <span>Pengumuman</span></a>
            </li>
            <li class="menu-header">Profil</li>
            <li class="{{ Request::is('profil') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('profil') }}"><i class="fas fa-user"></i> <span>Profil</span></a>
            </li>
        </ul>


        <!-- Mahasiswa -->
        @elseif($role === 'mahasiswa')
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="nav-item dropdown {{ ($type_menu ?? '') === 'dashboard' ? 'active' : '' }}">
                <a href="{{ route('mahasiswa.dashboard') }}" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
            </li>
                    <li class="nav-item dropdown {{ ($type_menu ?? '') === 'persyaratan' ? 'active' : '' }}">
                        <a href="#" class="nav-link has-dropdown"><i class="fas fa-file-alt"></i> <span>Berkas Persyaratan</span></a>
                        <ul class="dropdown-menu">
                            <li class="{{ request()->routeIs('dosen.dashboard') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('dosen.dashboard') }}">Persyaratan Sempro</a>
                            </li>
                            <li class="{{ request()->routeIs('dosen.dashboard') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('dosen.dashboard') }}">Persyaratan Tugas Akhir</a>
                            </li>
                        </ul>
                </li>
                <li class="nav-item dropdown {{ ($type_menu ?? '') === 'jadwal' ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-file-alt"></i> <span>Jadwal </span></a>
                    <ul class="dropdown-menu">
                        <li class="{{ request()->routeIs('dosen.dashboard') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('dosen.dashboard') }}">Jadwal Sempro</a>
                        </li>
                        <li class="{{ request()->routeIs('dosen.dashboard') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('dosen.dashboard') }}">Jadwal Sidang</a>
                        </li>
                        <li class ="{{ request()->routeIs('dosen.dashboard') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('dosen.dashboard') }}">Jadwal Sosialisasi</a>
                        </li>
                    </ul>
                </li>
            <li class="menu-header">Seminar Proposal</li>
            <li class="{{ Request::is('seminar') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('seminar') }}"><i class="fas fa-file-alt"></i> <span>Usulan Tugas Akhir</span></a>
            </li>
            <li class="menu-header">Sidang TA</li>
            <li class="{{ Request::is('sidang') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('sidang') }}"><i class="fas fa-file-alt"></i> <span>Hasil Sidang</span></a>
            <li class="menu-header">Profil</li>
            <li class="{{ Request::is('profil') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('profil') }}"><i class="fas fa-user"></i> <span>Profil</span></a>
            </ul>

        <!-- Dosen -->
        @elseif($role === 'dosen')
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="nav-item dropdown {{ ($type_menu ?? '') === 'dashboard' ? 'active' : '' }}">
                <a href="{{ route('dosen.dashboard') }}" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
            </li>
            <li class="menu-header">Pengumuman</li>
            <li class="{{ Request::is('pengumuman') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('pengumuman') }}"><i class="fas fa-bullhorn"></i> <span>Pengumuman</span></a>
            </li>
            <li class="menu-header">Profil</li>
            <li class="{{ Request::is('profil') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('profil') }}"><i class="fas fa-user"></i> <span>Profil</span></a>
            </li>
        </ul>
        @endif

    </aside>
</div>
