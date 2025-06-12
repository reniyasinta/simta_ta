@extends('layouts.app')

@section('title', 'Input Link Drive')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Input / Edit Link Drive Proyek</h1>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('panitia.sidang.drive.save', $sidang->id_sidang) }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Link Drive Proyek</label>
                <input type="url" name="link_drive_proyek" class="form-control"
                    value="{{ old('link_drive_proyek', $sidang->link_drive_proyek) }}"
                    placeholder="https://drive.google.com/...." required>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('sidang.final') }}" class="btn btn-secondary">Kembali</a>
        </form>

        {{-- Tombol hapus jika sudah ada --}}
        @if($sidang->link_drive_proyek)
        <form action="{{ route('panitia.sidang.drive.delete', $sidang->id_sidang) }}" method="POST" class="mt-3" onsubmit="return confirm('Yakin hapus link?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Hapus Link</button>
        </form>
        @endif

    </section>
</div>
@endsection
