@extends('layouts.app')

@section('title', 'Laporan TA Revisi')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Laporan TA Revisi</h1>
        </div>

        <div class="mb-3 d-flex justify-content-end">
            <a href="{{ route('mahasiswa.sidang.revisi.create') }}" class="btn btn-primary">+ Upload Revisi</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>File Revisi</th>
                        <th>Waktu Upload</th>
                        <th>Status Penguji 1</th>
                        <th>Status Penguji 2</th>
                        <th>Status Penguji 3</th>
                        <th>Catatan Penguji 1</th>
                        <th>Catatan Penguji 2</th>
                        <th>Catatan Penguji 3</th>
                    </tr>
                </thead>
                <tbody>

                @foreach($sidang->revisiUploads()->orderBy('uploaded_at', 'desc')->get() as $index => $upload)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><a href="{{ asset($upload->file_path) }}" target="_blank">Lihat</a></td>
                        <td>{{ $upload->uploaded_at }}</td>
                        <td>{{ $sidang->status_revisi_penguji_1 ?? '-' }}</td>
                        <td>{{ $sidang->status_revisi_penguji_2 ?? '-' }}</td>
                        <td>{{ $sidang->status_revisi_penguji_3 ?? '-' }}</td>
                        <td>{{ $sidang->catatan_penguji_1 ?? '-' }}</td>
                        <td>{{ $sidang->catatan_penguji_2 ?? '-' }}</td>
                        <td>{{ $sidang->catatan_penguji_3 ?? '-' }}</td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
