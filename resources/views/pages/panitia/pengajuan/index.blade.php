@extends('layouts.app')

@section('title', 'Pengajuan Mahasiswa')

@push('style')
<link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
@endpush

@section('main')
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>Daftar Pengajuan Mahasiswa</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="{{ url('home') }}">Dashboard</a></div>
                        <div class="breadcrumb-item">Pengajuan Mahasiswa</div>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="clearfix mb-3"></div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kelompok</th>
                                <th>Judul</th>
                                <th>Dosen 1</th>
                                <th>Dosen 2</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengajuanList as $key => $p)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $p->kelompok->nama ?? '-' }}</td>
                                    <td>{{ $p->judul_ta }}</td>
                                    <td>{{ $p->dosen1->nama ?? '-' }}</td>
                                    <td>{{ $p->dosen2->nama ?? 'Belum ditetapkan' }}</td>
                                    <td>
                                        <a href="" class="btn btn-sm btn-primary">
                                            Tentukan Dosen 2
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada pengajuan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
@endsection
