@extends('layouts.app')

@section('main')
    <div class="container-fluid py-5">
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>Buat Kelompok</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="{{ url('home') }}">Dashboard</a></div>
                        <div class="breadcrumb-item">Kelompok</div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="clearfix mb-3"></div>

                <form action="{{ route('kelompok.store') }}" method="POST">
                    @csrf

                    <div class="form-group mb-3">
                        <label>Anggota 1 (Anda)</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                    </div>

                    <div class="form-group mb-3">
                        <label for="anggota_2_id">Anggota 2 (opsional)</label>
                        <select name="anggota_2_id" class="form-control">
                            <option value="">-- Pilih Anggota 2 --</option>
                            @foreach ($mahasiswas as $mhs)
                                @if ($mhs->id !== auth()->id())
                                    <option value="{{ $mhs->id }}">{{ $mhs->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="anggota_3_id">Anggota 3 (opsional)</label>
                        <select name="anggota_3_id" class="form-control">
                            <option value="">-- Pilih Anggota 3 --</option>
                            @foreach ($mahasiswas as $mhs)
                                @if ($mhs->id !== auth()->id())
                                    <option value="{{ $mhs->id }}">{{ $mhs->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Buat Kelompok</button>
                        <a href="{{ route('kelompok.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </section>
        </div>
    </div>
@endsection
