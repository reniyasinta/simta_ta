@extends('layouts.app')

@section('title', 'Manage User')

@push('style')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
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

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Data User</h4>
        <div class="btn-group" role="group" aria-label="Aksi User">
            <a href="{{ route('admin.create') }}" class="btn btn-primary">+ Tambah User</a>
            <a href="{{ route('admin.import') }}" class="btn btn-primary">Import User</a>
            <a href="{{ route('template.user') }}" class="btn btn-primary">Download Template</a>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
                    <table id="table-users" class="table table-bordered table-striped">
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
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->role->name ?? '-' }}</td>
                                    <td>
                                        @if ($user->role_id == 4)
                                            {{ $user->nim ?? '-' }}
                                        @elseif (in_array($user->role_id, [1, 2, 3]))
                                            {{ $user->nip ?? '-' }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $user->prodi->nama_prodi ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('admin.edit', $user->id) }}"
                                           class="btn btn-primary btn-sm btn-action me-1"
                                           data-toggle="tooltip" title="Edit">
                                           <i class="fas fa-pencil-alt"></i>
                                        </a>

                                        <form action="{{ route('admin.destroy', $user->id) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Yakin ingin hapus user ini? Data tidak bisa dikembalikan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm btn-action"
                                                    data-toggle="tooltip" title="Delete">
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
            </div>
        </div>

    </section>
</div>

@endsection

@push('scripts')
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#table-users').DataTable({
                "language": {
                    "search": "Cari Nama / Email / NIM / NIP / Prodi:",
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Data tidak ditemukan",
                    "info": "Menampilkan _PAGE_ dari _PAGES_",
                    "infoEmpty": "Tidak ada data",
                    "infoFiltered": "(filtered from _MAX_ total records)"
                },
                "pageLength": 10
            });
        });
    </script>
@endpush
