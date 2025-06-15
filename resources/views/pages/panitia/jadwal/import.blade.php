@extends('layouts.app')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Import Jadwal {{ ucfirst($jenis) }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('panitia.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item active">Import Jadwal</div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

    <form action="{{ route('panitia.jadwal.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="jenis" value="{{ $jenis }}">

            <div class="form-group mb-3">
                <label>File Excel</label>
                <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" required>
                @error('file')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">Upload</button>
                <a href="{{ route('panitia.jadwal.jenis.index', ['jenis' => $jenis]) }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </section>
</div>
@endsection
