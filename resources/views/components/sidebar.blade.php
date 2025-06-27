<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ url('/') }}">
                <img src="{{ asset('img/LogoPoliban.png') }}" alt="Logo" style="height: 45px; margin-bottom: 2px;">
                <div style="line-height: 1; font-weight: bold;">SIMTA</div>
            </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ url('/') }}"><img src="{{ asset('img/LogoPoliban.png') }}" alt="Logo" style="height: 45px; margin-bottom: 2px;"></a>
        </div>

        @auth
        @php $roleId = auth()->user()->role_id; @endphp

        {{-- ================= Admin ================= --}}
        @if($roleId == 1)
        <ul class="sidebar-menu">
            <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
            </li>
            <li class="menu-header">Surat</li>
            <li class="{{ request()->routeIs('admin.surat.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.surat.index') }}"><i class="fas fa-file"></i> <span>Approval Surat</span></a>
            </li>
            <li class="menu-header">User Account</li>
            <li class="{{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.users') }}"><i class="fas fa-user"></i> <span>Manage User</span></a>
            </li>
        </ul>

        {{-- ================= Panitia ================= --}}
        @elseif($roleId == 2)
        <ul class="sidebar-menu">
            <li class="nav-item {{ request()->routeIs('panitia.dashboard') ? 'active' : '' }}">
                <a href="{{ route('panitia.dashboard') }}" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
            </li>
            <li class="menu-header">General</li>
            <li class="{{ request()->routeIs('panitia.kuota.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('panitia.kuota.index') }}"><i class="fas fa-user-cog"></i> <span>Manajemen Kuota Dosen</span></a>
            </li>
            <li class="{{ request()->routeIs('panitia.pengajuan.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('panitia.pengajuan.index') }}"><i class="fas fa-file-signature"></i> <span>Dosen Pembimbing 2</span></a>
            </li>
            <li class="{{ request()->routeIs('panitia.berkas.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('panitia.berkas.index') }}"><i class="fas fa-file-upload"></i> <span>Upload Berkas</span></a>
            </li>
            <li class="nav-item dropdown {{ request()->routeIs('panitia.jadwal.*', 'panitia.jadwal.yudisium.*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-calendar-alt"></i> <span>Daftar Jadwal</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ request()->is('panitia/jadwal/seminar') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('panitia.jadwal.jenis.index', ['jenis' => 'seminar']) }}">Seminar Proposal</a>
                    </li>
                    <li class="{{ request()->is('panitia/jadwal/sidang') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('panitia.jadwal.jenis.index', ['jenis' => 'sidang']) }}">Sidang TA</a>
                    </li>
                    <li class="{{ request()->is('panitia/jadwal/yudisium') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('panitia.jadwal.yudisium.index') }}">Yudisium</a>
                    </li>
                </ul>
            </li>
            <li class="{{ request()->routeIs('panitia.sempro.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('panitia.sempro.index') }}"><i class="fas fa-file-pdf"></i> <span>Berkas Sempro</span></a>
            </li>
            <li class="nav-item dropdown {{ request()->routeIs('panitia.sidang.*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-book"></i> <span>Berkas Sidang TA</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ request()->routeIs('panitia.sidang.draft') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('panitia.sidang.draft') }}">Pra</a>
                    </li>
                    <li class="{{ request()->routeIs('panitia.sidang.revisi') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('panitia.sidang.revisi') }}">Revisi</a>
                    </li>
                    <li class="{{ request()->routeIs('panitia.sidang.final') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('panitia.sidang.final') }}">Post</a>
                    </li>
                </ul>
            </li>
        </ul>

        {{-- ================= Dosen ================= --}}
        @elseif($roleId == 3)
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="{{ request()->routeIs('dosen.dashboard') ? 'active' : '' }}">
                <a href="{{ route('dosen.dashboard') }}" class="nav-link"><i class="fas fa-fire"></i> <span>Dashboard</span></a>
            </li>
            <li class="menu-header">Pembimbingan TA</li>
            <li class="{{ request()->routeIs('dosen.validasi') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dosen.validasi') }}"><i class="fas fa-check-circle"></i> <span>Validasi Pengajuan</span></a>
            </li>
            <li class="{{ request()->routeIs('dosen.sempro.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dosen.sempro.index') }}"><i class="fas fa-file-signature"></i> <span>Validasi Seminar Proposal</span></a>
            </li>
            <li class="{{ request()->routeIs('dosen.bimbingan') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dosen.bimbingan') }}"><i class="fas fa-comments"></i> <span>Data Bimbingan</span></a>
            </li>
            <li class="{{ request()->routeIs('dosen.sidang.draft') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dosen.sidang.draft') }}"><i class="fas fa-comments"></i> <span>Validasi Tugas Akhir</span></a>
            </li>
            <li class="{{ request()->routeIs('dosen.sidang.revisi') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dosen.sidang.revisi') }}"><i class="fas fa-comments"></i> <span>Persetujuan Perbaikan Sidang</span></a>
            </li>
        </ul>

        {{-- ================= Mahasiswa ================= --}}
        @elseif($roleId == 4)
        <ul class="sidebar-menu">
            <li class="{{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}">
                <a href="{{ route('mahasiswa.dashboard') }}" class="nav-link">
                    <i class="fas fa-fire"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="menu-header">General</li>
            <li class="{{ request()->routeIs('mahasiswa.berkas.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('mahasiswa.berkas.index') }}">
                    <i class="fas fa-file-upload"></i> <span>Berkas Persyaratan</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('mahasiswa.surat.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('mahasiswa.surat.index') }}">
                    <i class="fas fa-file-alt"></i> <span>Surat Pendukung</span>
                </a>
            </li>
            <li class="menu-header">Seminar Proposal</li>
            <li class="{{ request()->routeIs('mahasiswa.jadwal.seminar') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('mahasiswa.jadwal.seminar') }}">
                    <i class="fas fa-calendar-alt"></i> <span>Jadwal Seminar</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('kelompok.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('kelompok.index') }}">
                    <i class="fas fa-users"></i> <span>Data Kelompok</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('pengajuan.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('pengajuan.index') }}">
                    <i class="fas fa-file-signature"></i> <span>Pengajuan Pembimbing</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('mahasiswa.sempro.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('mahasiswa.sempro.index') }}">
                    <i class="fas fa-file-alt"></i> <span>Berkas SEMPRO</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('mahasiswa.undangan.index') && request()->get('jenis') == 'seminar' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('mahasiswa.undangan.index', ['jenis' => 'seminar']) }}">
                    <i class="fas fa-envelope-open-text"></i> <span>Undangan SEMPRO</span>
                </a>
            </li>
            <li class="menu-header">Tugas Akhir</li>
            <li class="{{ request()->routeIs('mahasiswa.jadwal.sidang') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('mahasiswa.jadwal.sidang') }}">
                    <i class="fas fa-calendar-check"></i> <span>Jadwal Sidang</span>
                </a>
            </li>
            <li class="nav-item dropdown {{ request()->routeIs('mahasiswa.sidang.*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i class="fas fa-file-alt"></i> <span>Upload Berkas Sidang</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ request()->routeIs('mahasiswa.sidang.draft') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('mahasiswa.sidang.draft') }}">Pra</a>
                    </li>
                    <li class="{{ request()->routeIs('mahasiswa.sidang.revisi') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('mahasiswa.sidang.revisi') }}">Revisi Laporan TA</a>
                    </li>
                    <li class="{{ request()->routeIs('mahasiswa.sidang.final') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('mahasiswa.sidang.final') }}">Post</a>
                    </li>
                </ul>
            </li>
            <li class="{{ request()->routeIs('mahasiswa.undangan.index') && request()->get('jenis') == 'sidang' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('mahasiswa.undangan.index', ['jenis' => 'sidang']) }}">
                    <i class="fas fa-envelope-open-text"></i> <span>Undangan Sidang</span>
                </a>
            </li>
        </ul>

        {{-- ================= Pimpinan ================= --}}
        @elseif($roleId == 5)
        <ul class="sidebar-menu">
            <li class="{{ request()->routeIs('pimpinan.dashboard') ? 'active' : '' }}">
                <a href="{{ route('pimpinan.dashboard') }}" class="nav-link">
                    <i class="fas fa-fire"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="menu-header">JADWAL</li>
            <li class="{{ request()->routeIs('pimpinan.jadwal.seminar') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('pimpinan.jadwal.seminar') }}">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Seminar Proposal</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('pimpinan.jadwal.sidang') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('pimpinan.jadwal.sidang') }}">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Sidang Tugas Akhir</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('pimpinan.jadwal.yudisium') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('pimpinan.jadwal.yudisium') }}">
                    <i class="fas fa-certificate"></i>
                    <span>Yudisium</span>
                </a>
            </li>
            <li class="menu-header">DATA REKAPITULASI</li>
            <li class="{{ request()->routeIs('pimpinan.pengajuan.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('pimpinan.pengajuan.index') }}">
                    <i class="fas fa-file-signature"></i> <span>Rekapitulasi DOSPEM</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('pimpinan.kuota.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('pimpinan.kuota.index') }}">
                    <i class="fas fa-users-cog"></i> <span>Rekapitulasi Kuota</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('pimpinan.surat.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('pimpinan.surat.index') }}">
                    <i class="fas fa-envelope"></i> <span>Rekapitulasi Surat</span>
                </a>
            </li>
             <li class="{{ request()->routeIs('pimpinan.sidang.final') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('pimpinan.sidang.final') }}">
                    <i class="fas fa-envelope"></i> <span>Rekapitulasi Berkas Mahasiswa</span>
                </a>
            </li>
        </ul>
        @endif
        @endauth
    </aside>
</div>
