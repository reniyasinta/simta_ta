@extends('layouts.app')

@section('title', 'Profil Mahasiswa')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Profil Mahasiswa</h1>
        </div>

        <div class="section-body">

            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="card profile-widget text-center shadow">
                        <div class="card-body">
                            <div class="profile-widget-header d-flex justify-content-center mb-3">
                                <img alt="Foto {{ $mahasiswa->nama_mhs }}"
                                    src="{{ asset($mahasiswa->foto) }}"
                                    class="rounded-circle"
                                    style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #6777ef;">
                            </div>

                            <h4 class="font-weight-bold mb-2">{{ $mahasiswa->nama_mhs }}</h4>
                            <p class="text-muted mb-4">Mahasiswa - {{ $mahasiswa->prodi->nama_prodi ?? '-' }}</p>

                            <table class="table table-sm table-borderless text-left mx-auto" style="width: 80%;">
                                <tr>
                                    <th style="width: 120px;">NIM</th>
                                    <td>: {{ $mahasiswa->nim_mhs }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>: {{ $mahasiswa->user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Prodi</th>
                                    <td>: {{ $mahasiswa->prodi->nama_prodi ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Semester</th>
                                    <td>: {{ $mahasiswa->semester ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>No Telepon</th>
                                    <td>: {{ $mahasiswa->no_telp ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="card-footer text-center bg-whitesmoke">
                            <a href="{{ route('dosen.bimbingan') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection
