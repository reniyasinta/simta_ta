@extends('layouts.app')

@section('title', 'Laporan TA Revisi')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Laporan TA Revisi</h1>
        </div>

        {{-- Notifikasi sukses --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Card untuk Tabel Revisi --}}
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Upload Revisi</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">

                        {{-- Tombol Upload --}}
                <div class="mb-3 d-flex justify-content-end">
                    <a href="{{ route('mahasiswa.sidang.revisi.create') }}" class="btn btn-primary">+ Upload Revisi</a>
                </div>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>File Revisi</th>
                                <th>Status Penguji 1</th>
                                <th>Status Penguji 2</th>
                                <th>Status Penguji 3</th>
                                <th>Catatan Penguji 1</th>
                                <th>Catatan Penguji 2</th>
                                <th>Catatan Penguji 3</th>
                            </tr>
                        </thead>
<tbody>
    @if ($sidang && $sidang->revisi_laporan)
        <tr>
            <td>1</td>
            <td><a href="{{ asset($sidang->revisi_laporan) }}" target="_blank">Lihat</a></td>
            <td>{{ $sidang->status_revisi_penguji_1 ?? '-' }}</td>
            <td>{{ $sidang->status_revisi_penguji_2 ?? '-' }}</td>
            <td>{{ $sidang->status_revisi_penguji_3 ?? '-' }}</td>
            <td>{{ $sidang->catatan_penguji_1 ?? '-' }}</td>
            <td>{{ $sidang->catatan_penguji_2 ?? '-' }}</td>
            <td>{{ $sidang->catatan_penguji_3 ?? '-' }}</td>
        </tr>
    @else
        <tr>
            <td colspan="8" class="text-center text-danger">Belum ada revisi yang diunggah.</td>
        </tr>
    @endif
</tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
