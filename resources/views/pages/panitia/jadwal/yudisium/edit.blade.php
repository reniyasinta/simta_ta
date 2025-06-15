@extends('layouts.app')

@section('title', 'Edit Jadwal Yudisium')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Edit Jadwal Yudisium</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('panitia.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('panitia.jadwal.yudisium.index') }}">Yudisium</a></div>
                <div class="breadcrumb-item active">Edit</div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h4>Form Edit</h4></div>
            <div class="card-body">
                <form action="{{ route('panitia.jadwal.yudisium.update', $jadwal->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="tanggal">Tanggal Yudisium</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ old('tanggal', $jadwal->tanggal) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="jam_mulai">Jam Mulai</label>
                        <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" value="{{ old('jam_mulai', $jadwal->jam_mulai) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="jam_selesai">Jam Selesai</label>
                        <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" value="{{ old('jam_selesai', $jadwal->jam_selesai) }}">
                    </div>

                    <div class="form-group">
                        <label for="ruangan">Tempat</label>
                        <input type="text" name="ruangan" id="ruangan" class="form-control" value="{{ old('ruangan', $jadwal->ruangan) }}" required>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('panitia.jadwal.yudisium.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>

    </section>
</div>
@endsection
