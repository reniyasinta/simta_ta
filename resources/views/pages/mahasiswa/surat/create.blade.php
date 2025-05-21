@extends('layouts.app')

@section('title', 'Suart Penelitian Mahasiswa')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                 <h1>Form Pengajuan Surat Penelitian</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ url('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('mahasiswa.surat.index') }}">surat</a></div>
                    <div class="breadcrumb-item">Form Surat</div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('mahasiswa.surat.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Judul TA</label>
                            <input type="text" name="judul_ta" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Perihal</label>
                            <input type="text" name="perihal" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tujuan Surat</label>
                            <input type="text" name="tujuan" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Dosen Pembimbing</label>
                            <input type="text" name="dosen_pembimbing" class="form-control" required>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Ajukan Surat</button>
                            <a href="{{ route('mahasiswa.surat.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
