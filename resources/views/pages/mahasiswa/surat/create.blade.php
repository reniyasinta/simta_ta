@extends('layouts.app')

@section('main')
<div class="container-fluid py-5">
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                 <h2>Form Pengajuan Surat Penelitian</h2>
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
                            <label class="form-label">Dosen Pembimbing</label>
                            <input type="text" name="dosen_pembimbing" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tujuan Surat</label>
                            <input type="text" name="tujuan" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Ajukan Surat
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
