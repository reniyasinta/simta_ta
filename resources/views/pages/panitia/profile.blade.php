@extends('layouts.app')
@section('title', 'Profile Panitia')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Profil Panitia</h1>
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
                                <img alt="Foto {{ $user->name }}"
                                    src="{{ $user->foto ? asset($user->foto) : asset('img/avatar/avatar-1.png') }}"
                                    class="rounded-circle"
                                    style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #6777ef;">
                            </div>

                            <h4 class="font-weight-bold mb-2">{{ $user->name }}</h4>
                            <p class="text-muted mb-4">Panitia - {{ $user->prodi->nama_prodi ?? '-' }}</p>

                            <table class="table table-sm table-borderless text-left mx-auto" style="width: 80%;">
                                <tr>
                                    <th>Email</th>
                                    <td>: {{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Prodi</th>
                                    <td>: {{ $user->prodi->nama_prodi ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="card-footer text-center bg-whitesmoke">
                            <a href="{{ route('panitia.profile_edit') }}" class="btn btn-primary btn-sm">
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
