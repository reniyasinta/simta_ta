@extends('layouts.app')

@section('title', 'Daftar Berkas Persyaratan')

@push('style')
<link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
@endpush

@section('main')
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>Daftar Berkas Persyaratan</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="{{ url('home') }}">Dashboard</a></div>
                        <div class="breadcrumb-item">Berkas</div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Berkas</th>
                                <th>Preview</th>
                                <th>Download</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($berkas as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->nama_berkas }}</td>
                                    <td>
                                        @if(Storage::disk('public')->exists($item->file_path))
                                            <a href="{{ Storage::url($item->file_path) }}" target="_blank" class="btn btn-info btn-sm">Lihat</a>
                                        @else
                                            <span class="text-danger">File tidak ditemukan</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('mahasiswa.berkas.download', $item->id_berkas) }}" class="btn btn-success btn-sm">Download</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada berkas tersedia.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
@endsection
