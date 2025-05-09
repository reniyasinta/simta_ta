@extends('layouts.app')

@section('main')
    <div class="container-fluid py-5">
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>Import Jadwal Seminar</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="{{ url('home') }}">Dashboard</a></div>
                        <div class="breadcrumb-item">Jadwal</div>
                        <div class="breadcrumb-item">Import</div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @elseif(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('jadwal.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-3">
                        <label>File Excel</label>
                        <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" required>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Upload</button>
                        <a href="{{ route('jadwal.create') }}" class="btn btn-secondary">Input Manual</a>
                    </div>
                </form>

            </section>
        </div>
    </div>
@endsection
