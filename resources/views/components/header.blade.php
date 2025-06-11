<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li>
                <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
            <li>
                <a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none">
                    <i class="fas fa-search"></i>
                </a>
            </li>
        </ul>
    </form>

    @php
        use Illuminate\Support\Facades\Auth;

        $user = Auth::user();
        $foto = asset('img/avatar/avatar-1.png'); // default avatar

        if ($user->role->name === 'dosen' && $user->dosen && $user->dosen->foto) {
            $foto = asset($user->dosen->foto); // sudah termasuk storage/
        } elseif ($user->role->name === 'mahasiswa' && $user->mahasiswa && $user->mahasiswa->foto) {
            $foto = asset($user->mahasiswa->foto); // gunakan path lengkap dari DB
        }
    @endphp

    <ul class="navbar-nav navbar-right">
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
            <img alt="image"
                src="{{ $foto }}"
                class="rounded-circle mr-1"
                style="width: 35px; height: 35px; object-fit: cover;">
                <div class="d-sm-none d-lg-inline-block">
                    {{ $user->name }}
                </div>
            </a>
@php
    $roleId = auth()->user()->role_id;
    if ($roleId == 1) {
        $profileRoute = url('profil'); // Admin
    } elseif ($roleId == 2) {
        $profileRoute = url('profil'); // Panitia
    } elseif ($roleId == 3) {
        $profileRoute = route('dosen.profile');
    } elseif ($roleId == 4) {
        $profileRoute = route('mahasiswa.profile');
    } else {
        $profileRoute = '#'; // fallback
    }
@endphp

<div class="dropdown-menu dropdown-menu-right">
    
    <a href="{{ $profileRoute }}" class="dropdown-item has-icon text-blue">
        <i class="far fa-user"></i> Profil
    </a>
    <div class="dropdown-divider"></div>
    <a href="#" class="dropdown-item has-icon text-danger"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</div>

        </li>
    </ul>
</nav>
