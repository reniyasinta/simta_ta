@extends('layouts.app')

@section('title', 'Pengaturan Link Drive Proyek')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Pengaturan Link Drive Proyek</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="#" method="POST">
            @csrf

            <div class="mb-3">
                <label for="config_value" class="form-label">Link Google Drive Proyek (ZIP)</label>
                <input type="url" name="config_value" class="form-control" placeholder="https://drive.google.com/..." value="{{ $linkConfig?->config_value }}" required>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Simpan Link</button>
            </div>
        </form>
    </section>
</div>
@endsection
