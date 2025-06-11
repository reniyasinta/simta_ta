@extends('layouts.app')

@section('title', 'Profil Panitia')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Profil Panitia</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header"><h4>Update Profil</h4></div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('panitia.profil.update') }}">
                            @csrf

                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                            </div>

                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                            </div>

                            <div class="form-group">
                                <label>Prodi</label>
                                <input type="text" class="form-control" value="{{ $prodi->nama_prodi ?? '-' }}" readonly>
                            </div>

                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>
@endsection
