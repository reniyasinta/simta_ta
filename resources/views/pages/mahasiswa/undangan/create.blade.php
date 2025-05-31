@extends('layouts.app')

@section('title', 'Upload Undangan ' . ucfirst($jenisAcara))

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Upload Undangan {{ ucfirst($jenisAcara) }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('home') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('mahasiswa.undangan.index', ['jenis' => $jenisAcara]) }}">Undangan {{ ucfirst($jenisAcara) }}</a></div>
                <div class="breadcrumb-item active">Upload</div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('mahasiswa.undangan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">
            <input type="hidden" name="penguji_id" value="{{ $pengujiId }}">
            <input type="hidden" name="jenis_acara" value="{{ $jenisAcara }}">

            <div class="form-group">
                <label>Upload File (PDF)</label>
                <input type="file" name="undangan" class="form-control" required accept=".pdf">
            </div>

            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary">Upload</button>
                <a href="{{ route('mahasiswa.undangan.index', ['jenis' => $jenisAcara]) }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </section>
</div>
@endsection
