@extends('layouts.app')



@section('main')
    <div class="container-fluid py-5">
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>Buat Kelompok</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="{{ url('home') }}">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="{{ route('kelompok.index') }}">Kelompok</a></div>
                        <div class="breadcrumb-item">Buat kelompok</div>

                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="clearfix mb-3"></div>
                <form action="{{ route('kelompok.store') }}" method="POST">
                    @csrf

                <div class="form-group mb-3">
                    <label>Anggota 1 (NIM)</label>
                    <input type="text" name="anggota_nim[]" class="form-control" placeholder="Masukkan NIM anggota 1" required>
                </div>

                <div class="form-group mb-3">
                    <label>Anggota 2 (NIM)</label>
                    <input type="text" name="anggota_nim[]" class="form-control" placeholder="Masukkan NIM anggota 2 (opsional)">
                </div>

                <div class="form-group mb-3">
                    <label>Anggota 3 (NIM)</label>
                    <input type="text" name="anggota_nim[]" class="form-control" placeholder="Masukkan NIM anggota 3 (opsional)">
                </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Buat Kelompok</button>
                        <a href="{{ route('kelompok.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>

            </section>
        </div>
    </div>
@endsection
