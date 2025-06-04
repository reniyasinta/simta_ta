@extends('layouts.app')

@section('title', 'Revisi Laporan')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Laporan TA Revisi</h1>
        </div>
        <div class="mb-3 d-flex justify-content-end">
            <a href="{{ route('mahasiswa.sidang.revisi.create') }}" class="btn btn-primary">+ Upload Berkas</a>
        </div>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Revisi Laporan</th>
                    <th>Status Revisi</th>
                    <th>Catatan Dosen</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>
                        @if($sidang && $sidang->revisi_laporan)
                            <a href="{{ asset($sidang->revisi_laporan) }}" target="_blank" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                        @else
                            <span class="text-muted">Belum Upload</span>
                        @endif
                    </td>
                    <td>{{ $sidang->status_revisi ?? '-' }}</td>
                    <td>{{ $sidang->catatan_dosen ?? '-' }}</td>

                </tr>
            </tbody>
        </table>

    </section>
</div>
@endsection
