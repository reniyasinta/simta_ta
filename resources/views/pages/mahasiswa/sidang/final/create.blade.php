@extends('layouts.app')

@push('styles')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Upload Laporan Akhir</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ url('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('mahasiswa.sidang.final') }}">Laporan Akhir</a></div>
                    <div class="breadcrumb-item">Upload</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @elseif (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Upload Laporan Akhir</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('mahasiswa.sidang.uploadFinal') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label>Laporan Akhir (PDF)</label>
                                        <input type="file" name="laporan_akhir" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Lembar Konsultasi (PDF)</label>
                                        <input type="file" name="lembar_konsultasi" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Hasil Sidang (PDF)</label>
                                        <input type="file" name="hasil_sidang" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-info">Upload</button>
                                    <a href="{{ route('mahasiswa.sidang.final') }}" class="btn btn-secondary">Kembali</a>
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
    <!-- JS Libraries -->
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/features-posts.js') }}"></script>
@endpush
