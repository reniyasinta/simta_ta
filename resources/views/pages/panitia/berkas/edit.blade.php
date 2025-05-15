@extends('layouts.app')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Berkas</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('pages.panitia.berkas.index') }}">Upload Berkas</a></div>
                    <div class="breadcrumb-item">Edit</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-header">
                                <h4>Form Edit Berkas</h4>
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

                                <form action="{{ route('panitia.berkas.update', $berkas->id_berkas) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-group mb-3">
                                        <label for="nama_berkas" class="fw-semibold">Nama Berkas</label>
                                        <input type="text" name="nama_berkas" id="nama_berkas" class="form-control" value="{{ old('nama_berkas', $berkas->nama_berkas) }}" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="kategori" class="fw-semibold">Kategori Berkas</label>
                                        <select name="kategori" id="kategori" class="form-control selectric" required>
                                            <option value="sempro" {{ $berkas->kategori == 'sempro' ? 'selected' : '' }}>Seminar Proposal</option>
                                            <option value="ta" {{ $berkas->kategori == 'ta' ? 'selected' : '' }}>Tugas Akhir</option>
                                            <option value="buku_pedoman" {{ $berkas->kategori == 'buku_pedoman' ? 'selected' : '' }}>Buku Pedoman</option>
                                        </select>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label for="file" class="fw-semibold">File PDF (kosongkan jika tidak ingin mengubah)</label>
                                        <input type="file" name="file" id="file" class="form-control" accept="application/pdf">
                                    </div>

                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        <a href="{{ route('pages.panitia.berkas.index') }}" class="btn btn-secondary">Batal</a>
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

@push('scripts')
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.selectric').selectric();
        });
    </script>
@endpush
