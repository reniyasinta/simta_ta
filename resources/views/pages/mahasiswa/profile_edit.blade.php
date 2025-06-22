@extends('layouts.app')
@section('title', 'Edit Profil Mahasiswa')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Edit Profil Mahasiswa</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-8 mx-auto">
                    <div class="card">
                        <form method="POST" action="{{ route('mahasiswa.profile.update') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="card-body">
                                <div class="form-group">
                                    <label>Nama Mahasiswa</label>
                                    <input type="text" name="nama_mhs" class="form-control" value="{{ old('nama_mhs', $mahasiswa->nama_mhs) }}" required>
                                </div>

                                <div class="form-group">
                                    <label>NIM</label>
                                    <input type="text" name="nim_mhs" class="form-control" value="{{ old('nim_mhs', $mahasiswa->nim_mhs) }}" required>
                                </div>

                                <div class="form-group">
                                    <label>Semester</label>
                                    <input type="number" name="semester" class="form-control" value="{{ old('semester', $mahasiswa->semester) }}" required>
                                </div>

                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                </div>

                                <div class="form-group">
                                    <label>No. Telepon</label>
                                    <input type="text" name="no_telp" class="form-control" value="{{ old('no_telp', $mahasiswa->no_telp) }}">
                                </div>

                                <div class="form-group">
                                    <label>Upload Foto</label>
                                    <input type="file" name="foto" class="form-control-file">

                                    @if ($mahasiswa->foto)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/uploads/foto_mahasiswa/' . $mahasiswa->foto) }}" alt="Foto Mahasiswa"
                                                style="width: 100px; height: 100px; object-fit: cover;" class="rounded-circle">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="card-footer text-right">
                                <a href="{{ route('mahasiswa.profile') }}" class="btn btn-secondary">Batal</a>
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
