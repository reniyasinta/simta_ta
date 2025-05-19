@extends('layouts.app')

@section('title', 'Manage User')

@push('style')
<link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
@endpush

@section('main')

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Daftar Pengguna</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ url('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Semua User</div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- Filter --}}
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.users') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label for="search" class="form-label">Cari</label>
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Nama / Email / NIM" value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="prodi_id" class="form-label">Prodi</label>
                                <select name="prodi_id" class="form-control">
                                    <option value="">-- Semua Prodi --</option>
                                    @foreach($prodis as $prodi)
                                        <option value="{{ $prodi->id }}" {{ request('prodi_id') == $prodi->id ? 'selected' : '' }}>
                                            {{ $prodi->nama_prodi }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="tahun" class="form-label">Tahun</label>
                                <select name="tahun" class="form-control">
                                    <option value="">-- Semua Tahun --</option>
                                    @foreach($tahunList as $tahun)
                                        <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>
                                            {{ $tahun }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="d-flex flex-wrap mt-4 col-md-3" style="gap: 10px;">

                                <button class="btn btn-secondary">Filter</button>
                                <a href="{{ route('admin.users') }}" class="btn btn-light">Reset</a>
                            </div>
                        </div>
                    </form>

                    {{-- Tombol aksi --}}
                    <div class="d-flex justify-content-start flex-wrap mt-4" style="gap: 10px;">
                        <a href="{{ route('admin.create') }}" class="btn btn-primary me-2 mb-2">+ Tambah User</a>
                        <a href="{{ route('admin.import') }}" class="btn btn-primary me-2 mb-2">Import User</a>
                        <a href="{{ route('template.user') }}" class="btn btn-primary mb-2">Download Template</a>
                    </div>
                </div>
            </div>

            {{-- Tabel --}}
            <div class="table-responsive">
                <div class="d-flex justify-content-end mb-3">
                    {{ $users->withQueryString()->links() }}
                </div>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>NIP/NIM</th>
                            <th>Prodi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $index => $user)
                            <tr>
                                <td>{{ $users->firstItem() + $index }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role->name ?? '-' }}</td>
                                <td>{{ $user->role_id == 4 ? $user->nim : $user->nip }}</td>
                                <td>{{ $user->prodi->nama_prodi ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.edit', $user->id) }}"
                                    class="btn btn-primary btn-action me-1"
                                    data-toggle="tooltip"
                                    title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>

                                    <form action="{{ route('admin.destroy', $user->id) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Yakin ingin hapus user ini? Data tidak bisa dikembalikan.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-danger btn-action"
                                                data-toggle="tooltip"
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada pengguna ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

@endsection
