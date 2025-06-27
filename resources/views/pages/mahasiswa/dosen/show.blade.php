@extends('layouts.app')

@section('title', 'Detail Dosen')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Detail Dosen</h1>
        </div>

        <div class="section-body">

            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="card profile-widget text-center shadow">
                        <div class="card-body">
                            <div class="profile-widget-header d-flex justify-content-center mb-3">
                                <img alt="Foto {{ $dosen->nama_dosen }}"
                                    src="{{ $dosen->foto ? asset('storage/uploads/foto_dosen/' . $dosen->foto) : asset('img/avatar/avatar-1.png') }}"
                                    class="rounded-circle"
                                    style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #6777ef;">
                            </div>

                            <h4 class="font-weight-bold mb-2">{{ $dosen->nama_dosen }}</h4>
                            <p class="text-muted mb-4">Dosen - {{ $dosen->prodi->nama_prodi ?? '-' }}</p>

                            <table class="table table-sm table-borderless text-left mx-auto" style="width: 80%;">
                                <tr>
                                    <th style="width: 120px;">NIP</th>
                                    <td>: {{ $dosen->nip_dosen }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>: {{ $dosen->user->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>No HP</th>
                                    <td>: {{ $dosen->no_telp ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Prodi</th>
                                    <td>: {{ $dosen->prodi->nama_prodi ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Kuota</th>
                                    <td>: {{ $kuota->kuota_bimbingan ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="card-footer text-center bg-whitesmoke">
                            <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-secondary btn-sm">
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
