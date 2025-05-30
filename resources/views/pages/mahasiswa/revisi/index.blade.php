@extends('layouts.app')

@section('title', 'Revisi Laporan')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Revisi Laporan</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="mb-3">
            <a href="{{ route('mahasiswa.revisi.upload.form') }}" class="btn btn-primary">Upload Revisi Laporan</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Status</th>
                        <th>Revisi Laporan</th>
                        <th>Catatan Dosen</th>
                    </tr>
                </thead>
                <tbody>
                    @if($sidang)
                        <tr>
                            <td>1</td>
                            <td>
                                @if($sidang->status === 'Diterima')
                                    <span class="badge bg-success">Diterima</span>
                                @elseif($sidang->status === 'Revisi')
                                    <span class="badge bg-warning text-dark">Revisi</span>
                                @else
                                    <span class="badge bg-secondary">Menunggu</span>
                                @endif
                            </td>
                            <td>
                                @if($sidang->revisi_laporan)
                                    <a href="{{ asset($sidang->revisi_laporan) }}" target="_blank" class="btn btn-sm btn-link">Download</a>
                                @else
                                    <span class="text-muted">Belum ada</span>
                                @endif
                            </td>
                            <td>{{ $sidang->catatan_dosen ?? '-' }}</td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="4" class="text-center">Belum ada data sidang.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
