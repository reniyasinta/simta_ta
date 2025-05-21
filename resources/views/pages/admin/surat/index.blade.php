@extends('layouts.app')

@section('title', 'Surat Penelitian Mahasiswa')

@push('style')
<link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Daftar Pengajuan Surat Penelitian</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ url('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Surat</div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped mt-3">
                    <thead>
                        <tr>
                            <th>Mahasiswa</th>
                            <th>Judul TA</th>
                            <th>Tujuan</th>
                            <th>Perihal</th>
                            <th>Dosen Pembimbing</th>
                            <th>Status</th>
                            <th>Preview</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($daftarSurat as $surat)
                            <tr>
                                <td>{{ $surat->mahasiswa?->nama_mhs ?? '-' }}</td>
                                <td>{{ $surat->judul_ta }}</td>
                                <td>{{ $surat->tujuan }}</td>
                                <td>{{ $surat->perihal }}</td>
                                <td>{{ $surat->dosen?->nama_dosen ?? $surat->dosen_pembimbing }}</td>
                                <td class="text-capitalize">{{ $surat->status ?? '-' }}</td>
                                <td>
                                    @if($surat->file_surat)
                                    <a href="{{ Storage::url($surat->file_surat) }}" target="_blank" class="text-success">Lihat</a>
                                    @else
                                        <span class="text-muted">Belum tersedia</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.surat.edit', $surat->id_surat) }}" class="btn btn-sm btn-primary">Upload</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Belum ada pengajuan surat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
@endsection
