@extends('layouts.app')
@section('title', 'Edit Profil Panitia')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Edit Profil Panitia</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-8 mx-auto">
                    <div class="card">
                        <form method="POST" action="{{ route('panitia.profile.update') }}" enctype="multipart/form-data">
                            @csrf

                            {{-- Tampilkan error validasi jika ada --}}
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="card-body">
                                <div class="form-group">
                                    <label>Nama Panitia</label>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ old('name', $user->name) }}" required>
                                </div>

                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control"
                                        value="{{ old('email', $user->email) }}" required>
                                </div>

                                <div class="form-group">
                                    <label>Prodi</label>
                                    <input type="text" class="form-control"
                                        value="{{ $user->prodi->nama_prodi ?? '-' }}" readonly>
                                    <input type="hidden" name="id_prodi" value="{{ $user->id_prodi }}">
                                </div>
                            </div>

                            <div class="card-footer text-right">
                                <a href="{{ route('panitia.profile') }}" class="btn btn-secondary">Batal</a>
                                <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
