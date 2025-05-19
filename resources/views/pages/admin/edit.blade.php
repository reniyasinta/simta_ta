@extends('layouts.app')

@section('main')
<div class="container-fluid py-5">
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h2>Edit Pengguna</h2>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ url('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('admin.users') }}">Semua User</a></div>
                    <div class="breadcrumb-item">Edit User</div>
                </div>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Nama</label>
                    <input type="text" name="name" id="name" class="form-control"
                           value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control"
                           value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="form-group">
                    <label for="password">Password (Biarkan kosong jika tidak diubah)</label>
                    <input type="password" name="password" id="password" class="form-control">
                </div>

                <div class="form-group">
                    <label for="role_id">Role</label>
                    <select name="role_id" id="roleSelect" class="form-control" required>
                        <option value="">-- Pilih Role --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}"
                                {{ $role->id == old('role_id', $user->role_id) ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" id="nip-group" style="{{ in_array(old('role_id', $user->role_id), [1,2,3]) ? '' : 'display:none;' }}">
                    <label for="nip">NIP</label>
                    <input type="text" name="nip" id="nip" class="form-control"
                           value="{{ old('nip', $user->nip) }}">
                </div>

                <div class="form-group" id="nim-group" style="{{ old('role_id', $user->role_id) == 4 ? '' : 'display:none;' }}">
                    <label for="nim">NIM</label>
                    <input type="text" name="nim" id="nim" class="form-control"
                           value="{{ old('nim', $user->nim) }}">
                </div>

                <div class="form-group">
                    <label for="id_prodi">Program Studi</label>
                    <select name="id_prodi" class="form-control">
                        <option value="">-- Pilih Prodi --</option>
                        @foreach ($prodis as $prodi)
                            <option value="{{ $prodi->id }}"
                                {{ old('id_prodi', $user->id_prodi) == $prodi->id ? 'selected' : '' }}>
                                {{ $prodi->nama_prodi }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mt-3">
                    <a href="{{ route('admin.users') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </section>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleNipNim() {
        const roleValue = parseInt(document.getElementById('roleSelect').value);
        const nipGroup = document.getElementById('nip-group');
        const nimGroup = document.getElementById('nim-group');

        nipGroup.style.display = [1, 2, 3].includes(roleValue) ? 'block' : 'none';
        nimGroup.style.display = roleValue === 4 ? 'block' : 'none';
    }

    document.getElementById('roleSelect').addEventListener('change', toggleNipNim);
    window.addEventListener('DOMContentLoaded', toggleNipNim);
</script>
@endsection
