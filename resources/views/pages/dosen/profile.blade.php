@extends('layouts.app')
@section('title', 'Profile Mahasiswa')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Profil Dosen</h1>
        </div>

        <div class="section-body">
            @if (session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
            @endif

            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="card profile-widget text-center shadow">
                        <div class="card-body">
                            <div class="profile-widget-header d-flex justify-content-center mb-3">
                                <img alt="Foto {{ $dosen->nama_dosen }}"
                                     src="{{ $dosen->foto ? asset('uploads/foto_dosen/' . $dosen->foto) : asset('img/avatar/avatar-1.png') }}"
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
                                    <th>Keahlian</th>
                                    <td>: {{ $dosen->keahlian ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>: {{ $user->email ?? '-' }}</td>
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
                                    <th>Kuota TI</th>
                                    <td>: 0 / 12</td>
                                </tr>
                                <tr>
                                    <th>Kuota SIKC</th>
                                    <td>: 0 / 3</td>
                                </tr>
                            </table>
                        </div>

                        <div class="card-footer text-center bg-whitesmoke">
                            <a href="{{ route('dosen.profile_edit') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit mr-1"></i> Edit Profil
                            </a>
                        </div>
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
