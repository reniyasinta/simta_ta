@extends('layouts.app')
@section('title', 'Edit Profil Pimpinan')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Edit Profil Pimpinan</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-8 mx-auto">
                    <div class="card">
                        <form method="POST" action="{{ route('pimpinan.profile.update') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="card-body">
                                <div class="form-group">
                                    <label>Nama Lengkap</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                                </div>

                                <div class="form-group">
                                    <label>NIP</label>
                                    <input type="text" name="nip" class="form-control" value="{{ old('nip', $user->nip) }}" required>
                                </div>

                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                </div>

                            <div class="card-footer text-right">
                                <a href="{{ route('pimpinan.profile') }}" class="btn btn-secondary">Batal</a>
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
