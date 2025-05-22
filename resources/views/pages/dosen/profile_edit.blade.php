@extends('layouts.app')
@section('title', 'Edit Profil Dosen')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Edit Profil Dosen</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-8 mx-auto">
                    <div class="card">
                        <form method="POST" action="{{ route('dosen.profile.update') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="card-body">
                                <div class="form-group">
                                    <label>Nama Dosen</label>
                                    <input type="text" name="nama_dosen" class="form-control" value="{{ old('nama_dosen', $dosen->nama_dosen) }}" required>
                                </div>

                                <div class="form-group">
                                    <label>NIP</label>
                                    <input type="text" name="nip_dosen" class="form-control" value="{{ old('nip_dosen', $dosen->nip_dosen) }}" required>
                                </div>

                                <div class="form-group">
                                    <label>Bidang Keahlian</label>
                                    <input type="text" name="keahlian" class="form-control" value="{{ old('keahlian', $dosen->keahlian) }}" required>
                                </div>

                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                </div>

                                <div class="form-group">
                                    <label>No HP</label>
                                    <input type="tel" name="no_telp" class="form-control" value="{{ old('no_telp', $dosen->no_telp) }}">
                                </div>

                                <div class="form-group">
                                    <label>Upload Foto</label>
                                    <input type="file" name="foto" class="form-control-file">
                                    @if ($dosen->foto)
                                        <div class="mt-2">
                                            <img src="{{ asset($dosen->foto) }}" alt="Foto Dosen"
                                                 style="width: 100px; height: 100px; object-fit: cover;" class="rounded-circle">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="card-footer text-right">
                                <a href="{{ route('dosen.profile') }}" class="btn btn-secondary">Batal</a>
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
