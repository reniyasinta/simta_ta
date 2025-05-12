@extends('layouts.app')

@section('main')
    <div class="container-fluid py-5">
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>Upload Berkas Persyaratan</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                        <div class="breadcrumb-item">Upload Berkas</div>
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

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('panitia.berkas.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="kategori">Kategori Berkas</label>
                                <select name="kategori" id="kategori" class="form-control" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="sempro">Seminar Proposal</option>
                                    <option value="ta">Tugas Akhir</option>
                                    <option value="buku_pedoman">Buku Pedoman</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="nama_berkas">Nama Berkas</label>
                                <input type="text" name="nama_berkas" id="nama_berkas" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="file">File (PDF, max 20MB)</label>
                                <input type="file" name="file" id="file" class="form-control-file" required>
                            </div>

                            <div class="text-right">
                                <a href="{{ route('panitia.berkas.index') }}" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-primary">Unggah Berkas</button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
