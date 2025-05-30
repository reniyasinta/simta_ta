@extends('layouts.app')

@section('title', 'Upload Berkas Final')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Upload Berkas Final</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="mb-3">
            <a href="{{ route('mahasiswa.final.upload.form') }}" class="btn btn-primary">Upload Berkas Final</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Laporan Akhir</th>
                        <th>Lembar Konsultasi</th>
                        <th>Hasil Sidang</th>
                    </tr>
                </thead>
                <tbody>
                    @if($sidang)
                        <tr>
                            <td>1</td>
                            <td>
                                @if($sidang->laporan_akhir)
                                    <a href="{{ asset($sidang->laporan_akhir) }}" target="_blank" class="btn btn-sm btn-link">Download</a>
                                @else
                                    <span class="text-muted">Belum ada</span>
                                @endif
                            </td>
                            <td>
                                @if($sidang->lembar_konsultasi)
                                    <a href="{{ asset($sidang->lembar_konsultasi) }}" target="_blank" class="btn btn-sm btn-link">Download</a>
                                @else
                                    <span class="text-muted">Belum ada</span>
                                @endif
                            </td>
                            <td>
                                @if($sidang->hasil_sidang)
                                    <a href="{{ asset($sidang->hasil_sidang) }}" target="_blank" class="btn btn-sm btn-link">Download</a>
                                @else
                                    <span class="text-muted">Belum ada</span>
                                @endif
                            </td>
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
