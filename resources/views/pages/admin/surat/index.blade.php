@extends('layouts.app')

@section('main')
<div class="container-fluid py-5">
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h2>Daftar Pengajuan Surat Penelitian</h2>
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
                            <th>Dosen Pembimbing</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($daftarSurat as $surat)
                            <tr>
                                <td>{{ $surat->mahasiswa?->nama_mhs ?? '-' }}</td>
                                <td>{{ $surat->judul_ta }}</td>
                                <td>{{ $surat->dosen?->nama_dosen ?? '-' }}</td>
                                <td class="text-capitalize">{{ $surat->status ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.surat.edit', $surat->id_surat) }}" class="btn btn-sm btn-primary">Proses</a>
                                    @if($surat->file_surat)
                                        <a href="{{ Storage::url($surat->file_surat) }}" target="_blank" class="btn btn-sm btn-success">Download</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada pengajuan surat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
@endsection
