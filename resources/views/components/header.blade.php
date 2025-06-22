<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li>
                <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>
    </form>

    @php
        use Illuminate\Support\Facades\Auth;

        $user = Auth::user();
        $foto = asset('img/avatar/avatar-1.png'); // default

switch ($user->role->name) {
    case 'dosen':
        if ($user->dosen && $user->dosen->foto) {
            $foto = asset('storage/uploads/foto_dosen/' . $user->dosen->foto);
        }
        break;
    case 'mahasiswa':
        if ($user->mahasiswa && $user->mahasiswa->foto) {
            $foto = asset('storage/uploads/foto_mahasiswa/' . $user->mahasiswa->foto);
        }
        break;
    case 'panitia':
    case 'admin':
        if ($user->foto) {
            $foto = asset('storage/uploads/foto_user/' . $user->foto);
        }
        break;
}


        // Handle route profil per role
        switch ($user->role_id) {
            case 1: // Admin
                $profileRoute = url('admin.profile');
                break;
            case 2: // Panitia
                $profileRoute = route('panitia.profile');
                break;
            case 3: // Dosen
                $profileRoute = route('dosen.profile');
                break;
            case 4: // Mahasiswa
                $profileRoute = route('mahasiswa.profile');
                break;
            default:
                $profileRoute = '#';
                break;
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
