@extends('layouts.app')

@section('title', 'Profile Dosen')

@push('style')
<link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
@endpush

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Profile Dosen</h1>
        </div>

        <div class="section-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="row mt-sm-4">
                <!-- Kolom Kiri: Tampilan Profil -->
                <div class="col-12 col-md-12 col-lg-5">
                    <div class="card profile-widget">
                        <div class="profile-widget-header">
                            <img alt="image"
                                 src="{{ $dosen->foto ? asset('uploads/foto_dosen/' . $dosen->foto) : asset('img/avatar/avatar-1.png') }}"
                                 class="rounded-circle profile-widget-picture"
                                 style="object-fit: cover; width: 100px; height: 100px;">
                            <div class="profile-widget-items">
                                <div class="profile-widget-item">
                                    <div class="profile-widget-item-label">Kuota Bimbingan TI</div>
                                    <div class="profile-widget-item-value">0/12</div>
                                </div>
                                <div class="profile-widget-item">
                                    <div class="profile-widget-item-label">Kuota Bimbingan SIKC</div>
                                    <div class="profile-widget-item-value">0/3</div>
                                </div>
                            </div>
                        </div>
                        <div class="profile-widget-description">
                            <h5 class="mb-2 font-weight-bold">{{ $dosen->nama_dosen }}</h5>
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <th style="width: 120px;">NIP</th>
                                    <td>: {{ $dosen->nip_dosen }}</td>
                                </tr>
                                <tr>
                                    <th>Keahlian</th>
                                    <td>: {{ $dosen->keahlian ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>: {{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th>No HP</th>
                                    <td>: {{ $dosen->no_telp ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Prodi</th>
                                    <td>: {{ $dosen->prodi->nama_prodi ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Form Edit Profil -->
                <div class="col-12 col-md-12 col-lg-7">
                    <div class="card">
                        <form method="post" action="{{ route('dosen.profile.update') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
                            @csrf
                            <div class="card-header">
                                <h4>Edit Profile</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <label>Nama</label>
                                        <input type="text" name="nama_dosen" class="form-control" value="{{ old('nama_dosen', $dosen->nama_dosen) }}" required>
                                    </div>
                                    <div class="form-group col-md-6 col-12">
                                        <label>NIP</label>
                                        <input type="text" name="nip_dosen" class="form-control" value="{{ old('nip_dosen', $dosen->nip_dosen) }}" required>
                                    </div>
                                    <div class="form-group col-md-6 col-12">
                                        <label>Bidang Keahlian</label>
                                        <input type="text" name="keahlian" class="form-control" value="{{ old('keahlian', $dosen->keahlian) }}" required>
                                    </div>
                                    <div class="form-group col-md-6 col-12">
                                        <label>Foto Profil</label>
                                        <input type="file" name="foto" class="form-control-file">
                                        @if ($dosen->foto)
                                            <small class="form-text text-muted">Foto saat ini: {{ $dosen->foto }}</small>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-7 col-12">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                    </div>
                                    <div class="form-group col-md-5 col-12">
                                        <label>No HP</label>
                                        <input type="tel" name="no_telp" class="form-control" value="{{ old('no_telp', $dosen->no_telp) }}">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="{{ asset('library/summernote/dist/summernote-bs4.js') }}"></script>
@endpush
