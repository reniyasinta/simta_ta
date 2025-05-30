@extends('layouts.app')

@section('title', 'Upload Revisi Laporan')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Upload Revisi Laporan</h1>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        
        <form action="{{ route('mahasiswa.revisi.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>File Revisi Laporan (PDF)</label>
                <input type="file" name="revisi_laporan" class="form-control" accept="application/pdf" required>
            </div>

            <button type="submit" class="btn btn-primary">Upload</button>
            <a href="{{ route('mahasiswa.revisi.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </section>
</div>
@endsection
