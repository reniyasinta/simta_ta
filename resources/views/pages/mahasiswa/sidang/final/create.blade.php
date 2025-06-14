@extends('layouts.app')

@section('title', 'Upload Laporan Akhir')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Upload Laporan Akhir</h1>
        </div>

        {{-- TAMPILKAN ERROR VALIDASI --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORM UPLOAD FINAL --}}
        <form action="{{ route('mahasiswa.sidang.final.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf

            <div class="mb-3">
                <label class="form-label">Laporan Akhir (PDF)</label>
                <input type="file" name="laporan_akhir_pdf" class="form-control" accept=".pdf" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Laporan Akhir (Word)</label>
                <input type="file" name="laporan_akhir_word" class="form-control" accept=".doc,.docx" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Berita Acara (PDF)</label>
                <input type="file" name="berita_acara" class="form-control" accept=".pdf" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Buku Manual (Word)</label>
                <input type="file" name="buku_manual" class="form-control" accept=".doc,.docx" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Halaman Pengesahan (PDF / Word)</label>
                <input type="file" name="halaman_pengesahan" class="form-control" accept=".pdf,.doc,.docx" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Link Drive Proyek</label>
                <input type="url" name="link_drive_proyek" class="form-control" placeholder="https://drive.google.com/..." required>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('mahasiswa.sidang.final') }}" class="btn btn-secondary me-2">Kembali</a>
    <button type="submit" class="btn btn-success">Upload</button>
            </div>
        </form>
    </section>
</div>
@endsection
