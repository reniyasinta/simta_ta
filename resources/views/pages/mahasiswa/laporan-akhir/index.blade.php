@extends('layouts.app')

@section('title', 'Upload Laporan Akhir')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Upload Laporan Akhir</h1>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('mahasiswa.laporan-akhir.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="laporan_akhir">File Laporan Akhir (PDF, max 20 MB)</label>
                <input type="file" name="laporan_akhir" class="form-control" required>
            </div>

            @if ($sidang)
                <div class="form-group mt-3">
                    <label>Status Final: </label>
                    <strong>{{ $sidang->status_final }}</strong>
                </div>
            @endif

            <button type="submit" class="btn btn-primary mt-3">Upload</button>
        </form>
    </section>
</div>
@endsection
