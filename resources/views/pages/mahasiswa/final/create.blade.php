@extends('layouts.app')

@section('title', 'Upload Berkas Final')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Upload Berkas Final</h1>
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

        <form action="{{ route('mahasiswa.final.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>Laporan Akhir (PDF)</label>
                <input type="file" name="laporan_akhir" class="form-control" accept="application/pdf" required>
            </div>

            <div class="form-group">
                <label>Lembar Konsultasi (PDF)</label>
                <input type="file" name="lembar_konsultasi" class="form-control" accept="application/pdf" required>
            </div>

            <div class="form-group">
                <label>Hasil Sidang (PDF)</label>
                <input type="file" name="hasil_sidang" class="form-control" accept="application/pdf" required>
            </div>

            <button type="submit" class="btn btn-primary">Upload</button>
            <a href="{{ route('mahasiswa.final.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </section>
</div>
@endsection
