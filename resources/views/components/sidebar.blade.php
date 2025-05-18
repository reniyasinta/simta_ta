<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ url('/') }}">
                <img src="{{ asset('img/LogoPoliban.png') }}" alt="Logo" style="height: 45px; margin-bottom: 2px;">
                <div style="line-height: 1; font-weight: bold;">SIMTA</div>
            </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ url('/') }}">ST</a>
        </div>

        @auth
        @php $roleId = auth()->user()->role_id; @endphp

        {{-- ================= Admin (role_id = 1) ================= --}}
        @if($roleId == 1)
        <ul class="sidebar-menu">
            <li class="nav-item dropdown {{ ($type_menu ?? '') === 'dashboard' ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
            </li>

            <li class="menu-header">Surat</li>
            <li><a class="nav-link" href="{{ url('profil') }}"><i class="fas fa-file"></i> <span>Approval Penelitian</span></a></li>

            <li class="menu-header">User Account</li>
            <li><a class="nav-link" href="{{ url('profil') }}"><i class="fas fa-user"></i> <span>Profil</span></a></li>

            <li><a class="nav-link" href="{{ route('admin.users') }}"><i class="fas fa-user"></i> <span>Manage User</span></a></li>
        </ul>

        {{-- ================= Panitia (role_id = 2) ================= --}}
        @elseif($roleId == 2)
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="nav-item dropdown {{ ($type_menu ?? '') === 'dashboard' ? 'active' : '' }}">
                <a href="{{ route('panitia.dashboard') }}" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
            </li>

            <li class="menu-header">Jadwal</li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-calendar"></i> <span>Jadwal</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('jadwal.index') }}">Daftar Jadwal</a></li>
                </ul>
            </li>
            <li class="menu-header">Pengajuan</li>
            <li><a class="nav-link" href="{{ route('panitia.pengajuan.index') }}"><i class="fas fa-file-signature"></i> <span>Pengajuan Dospem2</span></a>
            </li>

            <li class="menu-header">Berkas Persyaratan</li>
            <li><a class="nav-link" href="{{ route('pages.panitia.berkas.index') }}"><i class="fas fa-file-upload"></i> <span>Upload Berkas</span></a>
            </li>

            <li class="menu-header">Pengumuman</li>
            <li><a class="nav-link" href="{{ url('pengumuman') }}"><i class="fas fa-bullhorn"></i> <span>Pengumuman</span></a>
            </li>

            <li class="menu-header">Profil</li>
            <li><a class="nav-link" href="{{ url('profil') }}"><i class="fas fa-user"></i> <span>Profil</span></a></li>
        </ul>

        {{-- ================= Dosen (role_id = 3) ================= --}}
        @elseif($roleId == 3)
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="nav-item dropdown {{ ($type_menu ?? '') === 'dashboard' ? 'active' : '' }}">
                <a href="{{ route('dosen.dashboard') }}" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
            </li>

            <li class="menu-header">Pengumuman</li>
            <li><a class="nav-link" href="{{ url('pengumuman') }}"><i class="fas fa-bullhorn"></i> <span>Pengumuman</span></a></li>

            <li class="menu-header">Profil</li>
            <li>
                <a class="nav-link {{ request()->routeIs('dosen.profile') ? 'active' : '' }}" href="{{ route('dosen.profile') }}">
                    <i class="fas fa-user"></i> <span>Profil</span>
                </a>
            </li>


        {{-- ================= Mahasiswa (role_id = 4) ================= --}}
        @elseif($roleId == 4)
        <ul class="sidebar-menu">
            <li class="nav-item dropdown {{ ($type_menu ?? '') === 'dashboard' ? 'active' : '' }}">
                <a href="{{ route('mahasiswa.dashboard') }}" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
            </li>
            <li class="menu-header">General</li>
            <li>
                <a class="nav-link" href="{{ route('mahasiswa.berkas.index') }}"><i class="fas fa-file-upload">
                    </i> <span>Berkas Persyaratan</span></a></li>
            </li>
            <li><a class="nav-link" href="{{ route('kelompok.index') }}"><i class="fas fa-users"></i> <span>Kelompok</span></a></li>

            <li><a class="nav-link" href="{{ route('pengajuan.index') }}"><i class="fas fa-file-signature"></i> <span>Pengajuan Dospem1</span></a></li>

            <li class="menu-header">Seminar Proposal</li>
            <li><a class="nav-link" href="{{ url('seminar') }}"><i class="fas fa-file-alt"></i> <span>Usulan Tugas Akhir</span></a></li>

            <li class="menu-header">Sidang TA</li>
            <li><a class="nav-link" href="{{ url('sidang') }}"><i class="fas fa-file-alt"></i> <span>Hasil Sidang</span></a></li>

            <li class="menu-header">Profil</li>
            <li><a class="nav-link" href="{{ url('profil') }}"><i class="fas fa-user"></i> <span>Profil</span></a></li>
        </ul>
        @endif
        @endauth
    </aside>
</div>
