@extends('layouts.app')

@section('main')
    <div class="container-fluid py-5">
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>Daftar Berkas Persyaratan</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="{{ url('home') }}">Dashboard</a></div>
                        <div class="breadcrumb-item">Upload Berkas</div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="mb-3 text-right">
                    <a href="{{ route('panitia.berkas.create') }}" class="btn btn-success">+ Upload Berkas</a>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Berkas</th>
                                <th>Kategori</th>
                                <th>Preview</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($berkas as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->nama_berkas }}</td>
                                    <td>{{ ucfirst($item->kategori) }}</td>
                                    <td>
                                        <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" class="btn btn-info btn-sm">Lihat</a>
                                    </td>
                                    <td>
                                        <a href="{{ route('panitia.berkas.edit', $item->id_berkas) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('panitia.berkas.destroy', $item->id_berkas) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus berkas ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada berkas diunggah.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
@endsection
