@extends('layouts.app')

@section('title', 'Upload Revisi Laporan')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Upload Revisi Laporan</h1>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('mahasiswa.revisi-laporan.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="revisi_laporan">File Revisi Laporan (PDF, max 20 MB)</label>
                <input type="file" name="revisi_laporan" class="form-control" required>
            </div>

            @if ($sidang)
                <div class="form-group mt-3">
                    <label>Status Revisi: </label>
                    <strong>{{ $sidang->status_revisi }}</strong>
                </div>

                @if ($sidang->catatan_dosen)
                    <div class="form-group mt-3">
                        <label>Catatan Dosen:</label>
                        <div class="alert alert-info">{{ $sidang->catatan_dosen }}</div>
                    </div>
                @endif
            @endif

            <button type="submit" class="btn btn-primary mt-3">Upload</button>
        </form>
    </section>
</div>
@endsection
