@extends('layouts.app')

@section('title', 'Upload Laporan TA')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Upload Laporan TA</h1>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('mahasiswa.laporan-ta.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="laporan_TA">File Laporan TA (PDF, max 20 MB)</label>
                <input type="file" name="laporan_TA" class="form-control" required>
            </div>

            @if ($sidang)
                <div class="form-group mt-3">
                    <label>Status Draft: </label>
                    <strong>{{ $sidang->status_draft }}</strong>
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
