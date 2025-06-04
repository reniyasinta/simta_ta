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
            <li><a class="nav-link" href="{{ route('admin.surat.index') }}"><i class="fas fa-file"></i> <span>Approval Surat</span></a></li>

            <li class="menu-header">User Account</li>
            <li><a class="nav-link" href="{{ url('profil') }}"><i class="fas fa-user"></i> <span>Profil</span></a></li>

            <li><a class="nav-link" href="{{ route('admin.users') }}"><i class="fas fa-user"></i> <span>Manage User</span></a></li>
        </ul>

        {{-- ================= Panitia (role_id = 2) ================= --}}
        @elseif($roleId == 2)
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="nav-item {{ ($type_menu ?? '') === 'dashboard' ? 'active' : '' }}">
                <a href="{{ route('panitia.dashboard') }}" class="nav-link">
                    <i class="fas fa-fire"></i> <span>Dashboard</span>
                </a>
            </li>
                        <li class="nav-item {{ ($type_menu ?? '') === 'kuota' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('panitia.kuota.index') }}">
                    <i class="fas fa-user-cog"></i> <span>Manajemen Kuota Dosen</span>
                </a>
            </li>
            <li class="menu-header">General</li>

            <li class="nav-item {{ ($type_menu ?? '') === 'berkas' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('pages.panitia.berkas.index') }}">
                    <i class="fas fa-file-upload"></i> <span>Upload Berkas</span>
                </a>
            </li>

            <li class="nav-item dropdown {{ request()->is('panitia/jadwal*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-calendar-alt"></i> <span>Daftar Jadwal</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ request()->is('panitia/jadwal/seminar*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('jadwal.seminar.index') }}">Seminar Proposal</a>
                    </li>
                    <li class="{{ request()->is('panitia/jadwal/sidang*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('jadwal.sidang.index') }}">Sidang TA</a>
                    </li>
                    <li class="{{ request()->is('panitia/jadwal/yudisium*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('jadwal.yudisium.index') }}">Yudisium</a>
                </ul>
            </li>

            <li class="nav-item {{ ($type_menu ?? '') === 'pengajuan' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('panitia.pengajuan.index') }}">
                    <i class="fas fa-file-signature"></i> <span>Pengajuan Dospem 2</span>
                </a>
            </li>
            <li class="menu-header">Sempro</li>
            <li class="{{ request()->routeIs('panitia.sempro.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('panitia.sempro.index') }}">
                    <i class="fas fa-file-pdf"></i> <span>Berkas Sempro</span>
                </a>
            </li>
<li class="menu-header">Tugas Akhir</li>

<li class="nav-item dropdown {{ request()->routeIs('panitia.sidang.*') ? 'active' : '' }}">
    <a href="#" class="nav-link has-dropdown">
        <i class="fas fa-book"></i> <span>Berkas Sidang TA</span>
    </a>
    <ul class="dropdown-menu">
        <li class="{{ request()->routeIs('sidang.draft') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('sidang.draft') }}">
                <i class="fas fa-file-alt"></i> Laporan TA
            </a>
        </li>
        <li class="{{ request()->routeIs('sidang.revisi') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('sidang.revisi') }}">
                <i class="fas fa-file-signature"></i> Revisi Laporan TA
            </a>
        </li>
        <li class="{{ request()->routeIs('sidang.final') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('sidang.final') }}">
                <i class="fas fa-file-upload"></i> Laporan Final TA
            </a>
        </li>
    </ul>
</li>



            <li class="menu-header">Profil</li>
            <li class="nav-item {{ ($type_menu ?? '') === 'profil' ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('profil') }}">
                    <i class="fas fa-user"></i> <span>Profil</span>
                </a>
            </li>
        </ul>


        {{-- ================= Dosen (role_id = 3) ================= --}}
        @elseif($roleId == 3)
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="{{ ($type_menu ?? '') === 'dashboard' ? 'active' : '' }}">
                <a href="{{ route('dosen.dashboard') }}" class="nav-link">
                    <i class="fas fa-fire"></i> <span>Dashboard</span>
                </a>
            </li>

            <li class="menu-header">Pembimbingan TA</li>
            <li>
                <a class="nav-link {{ request()->routeIs('dosen.validasi') ? 'active' : '' }}" href="{{ route('dosen.validasi') }}">
                    <i class="fas fa-check-circle"></i> <span>Validasi Pengajuan</span>
                </a>
            </li>

            <li>
                <a class="nav-link {{ request()->routeIs('dosen.bimbingan') ? 'active' : '' }}" href="{{ route('dosen.bimbingan') }}">
                    <i class="fas fa-comments"></i> <span>Data Bimbingan</span>
                </a>
            </li>
    <li class="nav-item dropdown {{ request()->is('dosen/sidang/*') ? 'active' : '' }}">
    <a href="#" class="nav-link has-dropdown"><i class="fas fa-file-signature"></i> <span>Sidang TA</span></a>
    <ul class="dropdown-menu">
        <li>
            <a class="nav-link {{ request()->routeIs('dosen.sidang.draft') ? 'active' : '' }}"
               href="{{ route('dosen.sidang.draft') }}">
               Laporan TA
            </a>
        </li>
        <li>
            <a class="nav-link {{ request()->routeIs('dosen.sidang.revisi') ? 'active' : '' }}"
               href="{{ route('dosen.sidang.revisi') }}">
               ACC Revisi Laporan TA
            </a>
        </li>
    </ul>
</li>


            <li class="menu-header">Data Pengujian TA</li>
            <li>
                <a class="nav-link {{ request()->is('dosen/seminar-proposal') ? 'active' : '' }}" href="{{ url('seminar proposal') }}">
                    <i class="fas fa-chalkboard-teacher"></i> <span>Seminar Proposal</span>
                </a>
            </li>
            <li>
                <a class="nav-link {{ request()->is('dosen/sidang-ta') ? 'active' : '' }}" href="{{ url('seminar proposal') }}">
                    <i class="fas fa-gavel"></i> <span>Sidang TA</span>
                </a>
            </li>

            <li class="menu-header">Profil</li>
            <li>
                <a class="nav-link {{ request()->routeIs('dosen.profile') ? 'active' : '' }}" href="{{ route('dosen.profile') }}">
                    <i class="fas fa-user"></i> <span>Profil</span>
                </a>
            </li>
        </ul>



        {{-- ================= Mahasiswa (role_id = 4) ================= --}}
       @elseif($roleId == 4)
        <ul class="sidebar-menu">

            {{-- Dashboard --}}
            <li class="nav-item {{ ($type_menu ?? '') === 'dashboard' ? 'active' : '' }}">
                <a href="{{ route('mahasiswa.dashboard') }}" class="nav-link">
                    <i class="fas fa-fire"></i> <span>Dashboard</span>
                </a>
            </li>

            {{-- General --}}
            <li class="menu-header">General</li>

            <li>
            <a class="nav-link" href="{{ route('mahasiswa.jadwal.index') }}">
                    <i class="fas fa-calendar-alt"></i> <span>Jadwal Saya</span>
                </a>
            </li>

            <li>
                <a class="nav-link" href="{{ route('mahasiswa.berkas.index') }}">
                    <i class="fas fa-file-upload"></i> <span>Berkas Persyaratan</span>
                </a>
            </li>

            <li>
                <a class="nav-link" href="{{ route('mahasiswa.surat.index') }}">
                    <i class="fas fa-file-alt"></i> <span>Surat Pendukung</span>
                </a>
            </li>

            {{-- Upload Undangan (Dropdown) --}}
            <li class="nav-item dropdown {{ request()->is('mahasiswa/undangan*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i class="fas fa-envelope-open-text"></i> <span>Upload Undangan</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ request()->is('mahasiswa/undangan?jenis=seminar') || request()->fullUrlIs('undangan*seminar') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('mahasiswa.undangan.index', ['jenis' => 'seminar']) }}">Undangan Seminar</a>
                    </li>
                    <li class="{{ request()->is('mahasiswa/undangan?jenis=sidang') || request()->fullUrlIs('undangan*sidang') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('mahasiswa.undangan.index', ['jenis' => 'sidang']) }}">Undangan Sidang</a>
                    </li>
                </ul>
            </li>

            {{-- Seminar Proposal --}}
            <li class="menu-header">Seminar Proposal</li>

            <li>
                <a class="nav-link" href="{{ route('kelompok.index') }}">
                    <i class="fas fa-users"></i> <span>Kelompok</span>
                </a>
            </li>

            <li>
                <a class="nav-link" href="{{ route('pengajuan.index') }}">
                    <i class="fas fa-file-signature"></i> <span>Pengajuan Dospem1</span>
                </a>
            </li>

            <li>
                <a class="nav-link" href="{{ route('mahasiswa.sempro.index') }}">
                    <i class="fas fa-file-alt"></i> <span>berkas sempro</span>
                </a>
            </li>

            {{-- Sidang TA --}}
<li class="nav-item dropdown {{ request()->is('mahasiswa/sidang/*') ? 'active' : '' }}">
    <a href="#" class="nav-link has-dropdown"><i class="fas fa-file-alt"></i> <span>Sidang TA</span></a>
    <ul class="dropdown-menu">
        <li><a class="nav-link {{ request()->routeIs('mahasiswa.sidang.draft') ? 'active' : '' }}" href="{{ route('mahasiswa.sidang.draft') }}">Laporan TA Draft</a></li>
        <li><a class="nav-link {{ request()->routeIs('mahasiswa.sidang.revisi') ? 'active' : '' }}" href="{{ route('mahasiswa.sidang.revisi') }}">Revisi Laporan</a></li>
        <li><a class="nav-link {{ request()->routeIs('mahasiswa.sidang.final') ? 'active' : '' }}" href="{{ route('mahasiswa.sidang.final') }}">Laporan Akhir</a></li>
    </ul>
</li>



            {{-- Profil --}}
            <li class="menu-header">Profil</li>

            <li>
                <a class="nav-link" href="{{ route('mahasiswa.profile') }}">
                    <i class="fas fa-user"></i> <span>Profil</span>
                </a>
            </li>
        </ul>
                @endif
        @endauth
    </aside>
</div>
