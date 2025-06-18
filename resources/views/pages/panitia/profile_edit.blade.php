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

                            <div class="card-body">
                                <div class="form-group">
                                    <label>Nama Panitia</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                                </div>

                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                </div>

                                <div class="form-group">
                                    <label>Prodi</label>
                                    <select name="id_prodi" class="form-control" required>
                                        <option value="">-- Pilih Prodi --</option>
                                        @foreach($prodis as $prodi)
                                            <option value="{{ $prodi->id_prodi }}" {{ old('id_prodi', $user->id_prodi) == $prodi->id_prodi ? 'selected' : '' }}>
                                                {{ $prodi->nama_prodi }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Upload Foto</label>
                                    <input type="file" name="foto" class="form-control-file">
                                    @if ($user->foto)
                                        <div class="mt-2">
                                            <img src="{{ asset($user->foto) }}" alt="Foto Panitia"
                                                style="width: 100px; height: 100px; object-fit: cover;" class="rounded-circle">
                                        </div>
                                    @endif
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
