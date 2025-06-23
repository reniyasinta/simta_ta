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
        if ($user->panitia && $user->panitia->foto) {
            $foto = asset('storage/uploads/foto_panitia/' . $user->panitia->foto);
        }
        break;
    case 'admin':
        if ($user->admin && $user->admin->foto) {
            $foto = asset('storage/uploads/foto_admin/' . $user->admin->foto);
        }
        break;
    case 'pimpinan':
        if ($user->pimpinan && $user->pimpinan->foto) {
            $foto = asset('storage/uploads/foto_pimpinan/' . $user->pimpinan->foto);
        }
        break;
}


        // Handle route profil per role
        switch ($user->role_id) {
            case 1: // Admin
                $profileRoute = route('admin.profile');
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
            case 5: // Pimpinan
                $profileRoute = route('pimpinan.profile');
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
