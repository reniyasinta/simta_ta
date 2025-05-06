@extends('layouts.app')

@section('main')
<div class="container-fluid py-5">
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h2>Tambah Pengguna</h2>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ route('admin.users') }}">Daftar User</a></div>
                    <div class="breadcrumb-item active">Tambah User</div>
                </div>
            </div>

            <form action="{{ route('admin.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" required placeholder="Masukkan nama lengkap">
                </div>

                <div class="form-group">
                    <label for="email">Email Pengguna</label>
                    <input type="email" name="email" class="form-control" required placeholder="Masukkan email">
                </div>

                <div class="form-group">
                    <label for="password">Password Default</label>
                    <input type="password" name="password" class="form-control" required placeholder="Masukkan password">
                </div>

                <div class="form-group">
                    <label for="role_id">Peran/Role</label>
                    <select name="role_id" class="form-control" required>
                        <option value="">-- Pilih Role --</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.users') }}" class="btn btn-secondary">Batal</a>
            </form>
        </section>
    </div>
</div>
@endsection
