@extends('layouts.app')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Tambah Pengguna</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('home') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('admin.users') }}">Semua User</a></div>
                <div class="breadcrumb-item active">Tambah User</div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h4 class="mb-0">Form Tambah Pengguna</h4>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" required placeholder="Masukkan nama lengkap" value="{{ old('name') }}">
                    </div>

                    <div class="form-group">
                        <label for="email">Email Pengguna</label>
                        <input type="email" name="email" class="form-control" required placeholder="Masukkan email" value="{{ old('email') }}">
                    </div>

                    <div class="form-group">
                        <label for="password">Password Default</label>
                        <input type="password" name="password" class="form-control" required placeholder="Masukkan password">
                    </div>

                    <div class="form-group">
                        <label for="role_id">Peran/Role</label>
                        <select name="role_id" id="roleSelect" class="form-control" required>
                            <option value="">-- Pilih Role --</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" id="nip-group" style="display: none;">
                        <label for="nip">NIP (Admin / Dosen / Panitia / Pimpinan)</label>
                        <input type="text" name="nip" id="nip" class="form-control" placeholder="Masukkan NIP" value="{{ old('nip') }}">
                    </div>

                    <div class="form-group" id="nim-group" style="display: none;">
                        <label for="nim">NIM (Mahasiswa)</label>
                        <input type="text" name="nim" id="nim" class="form-control" placeholder="Masukkan NIM" value="{{ old('nim') }}">
                    </div>

                    <div class="form-group">
                        <label for="id_prodi">Program Studi</label>
                        <select name="id_prodi" id="prodiSelect" class="form-control">
                            <option value="">-- Pilih Prodi --</option>
                            @foreach ($prodis as $prodi)
                                <option value="{{ $prodi->id }}" {{ old('id_prodi') == $prodi->id ? 'selected' : '' }}>
                                    {{ $prodi->nama_prodi }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mt-3 text-right">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('admin.users') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
    function toggleNipNim() {
        const roleValue = parseInt(document.getElementById('roleSelect').value);
        const nipGroup = document.getElementById('nip-group');
        const nimGroup = document.getElementById('nim-group');
        const prodiSelect = document.getElementById('prodiSelect');

        // Reset
        nipGroup.style.display = 'none';
        nimGroup.style.display = 'none';
        prodiSelect.disabled = false;

        // Show input sesuai role
        if ([1, 2, 3].includes(roleValue)) {
            nipGroup.style.display = 'block';
        } else if (roleValue === 4) {
            nimGroup.style.display = 'block';
        } else if (roleValue === 5) {
            nipGroup.style.display = 'block';
            prodiSelect.value = "";
            prodiSelect.disabled = true;
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        toggleNipNim(); // saat load
        document.getElementById('roleSelect').addEventListener('change', toggleNipNim);
    });
</script>
@endsection
