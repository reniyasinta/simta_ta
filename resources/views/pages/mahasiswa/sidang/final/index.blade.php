@extends('layouts.app')

@section('title', 'Laporan Akhir')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Laporan Akhir</h1>
        </div>
        <div class="mb-3 d-flex justify-content-end">
            <a href="{{ route('mahasiswa.sidang.final.create') }}" class="btn btn-primary">+ Upload Berkas</a>
        </div>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Laporan Akhir</th>
                    <th>Lembar Konsultasi</th>
                    <th>Hasil Sidang</th>
                    <th>Status Final</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>
                        @if($sidang && $sidang->laporan_akhir)
                            <a href="{{ asset($sidang->laporan_akhir) }}" target="_blank" class="btn btn-sm btn-info">
                                Lihat
                            </a>
                        @else
                            <span class="text-muted">Belum Upload</span>
                        @endif
                    </td>

                    <td>
                        @if($sidang && $sidang->lembar_konsultasi)
                            <a href="{{ asset($sidang->lembar_konsultasi) }}" target="_blank" class="btn btn-sm btn-info">
                                Lihat
                            </a>
                        @else
                            <span class="text-muted">Belum Upload</span>
                        @endif
                    </td>

                    <td>
                        @if($sidang && $sidang->hasil_sidang)
                            <a href="{{ asset($sidang->hasil_sidang) }}" target="_blank" class="btn btn-sm btn-info">
                                Lihat
                            </a>
                        @else
                            <span class="text-muted">Belum Upload</span>
                        @endif
                    </td>

                    <td>{{ $sidang->status_final ?? '-' }}</td>
                </tr>
            </tbody>
        </table>
    </section>
</div>
@endsection
