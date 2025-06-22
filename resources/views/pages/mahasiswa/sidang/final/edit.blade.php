@extends('layouts.app')

@section('title', 'Edit ' . $label)

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Edit {{ $label }}</h1>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form Edit {{ $label }}</h4>
                </div>
                <div class="card-body">

                    {{-- Notifikasi Error --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Form Edit --}}
                    <form action="{{ route('mahasiswa.sidang.final.update', $jenis) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @if($jenis === 'link_drive_proyek')
                            <div class="mb-3">
                                <label for="file" class="form-label">Link Drive Proyek</label>
                                <input type="url" name="file" id="file" class="form-control"
                                       value="{{ old('file', $sidang->$jenis ?? '') }}"
                                       required placeholder="https://drive.google.com/..." />
                            </div>
                        @else
                            <div class="mb-3">
                                <label for="file" class="form-label">{{ $label }}</label>
                                <input type="file" name="file" id="file" class="form-control" required />
                            </div>
                        @endif

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('mahasiswa.sidang.final') }}" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>
</div>
@endsection
