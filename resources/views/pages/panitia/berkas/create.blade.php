@extends('layouts.app')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Upload Berkas Persyaratan</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Upload Berkas</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-8">
                    <div class="card shadow">
                        <div class="card-header">
                            <h4>Form Upload Berkas</h4>
                        </div>

                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('panitia.berkas.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group mb-3">
                                    <label for="nama_berkas" class="fw-semibold">Nama Berkas</label>
                                    <input type="text" name="nama_berkas" id="nama_berkas" class="form-control" value="{{ old('nama_berkas') }}" required>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="file" class="fw-semibold">File</label>
                                    <input type="file" name="file" id="file" class="form-control"
                                           accept=".pdf,.ppt,.pptx,.xls,.xlsx,.doc,.docx,.txt,.jpeg,.jpg,.png,.rar,.zip" required>
                                    <small class="form-text text-muted">
                                        Format yang didukung: pdf, ppt(x), xls(x), doc(x), txt, jpg, png, zip, rar — Maks 20MB
                                    </small>
                                </div>

                                <div class="text-end">
                                    <a href="{{ route('panitia.berkas.index') }}" class="btn btn-secondary">Kembali</a>
                                    <button type="submit" class="btn btn-primary">Unggah Berkas</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
