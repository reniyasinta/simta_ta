@extends('layouts.app')

@section('title', 'Edit Surat Penelitian')

@push('style')
<link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Proses Surat Penelitian</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ route('admin.users') }}">Daftar Surat</a></div>
                    <div class="breadcrumb-item active">Upload Surat</div>
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

            <form action="{{ route('admin.surat.update', $surat->id_surat) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')


                <div class="form-group">
                    <label for="file_surat">Upload File Surat (PDF)</label>
                    <input type="file" name="file_surat" class="form-control" accept=".pdf">
                    @if ($surat->file_surat)
                        <small>File saat ini: <a href="{{ Storage::url($surat->file_surat) }}" target="_blank">Lihat Surat</a></small>
                    @endif
                </div>

                <div class="form-group mt-3">
                    <a href="{{ route('admin.surat.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-success">Simpan & Upload</button>
                </div>
            </form>
        </section>
    </div>
</div>
@endsection
