@extends('layouts.app')

@section('title', 'Edit ' . $label)

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Edit {{ $label }}</h1>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('mahasiswa.sidang.final.update', $jenis) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="file">File Baru</label>
                        <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" required>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Upload</button>
                    <a href="{{ route('mahasiswa.sidang.final') }}" class="btn btn-secondary mt-3">Kembali</a>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
