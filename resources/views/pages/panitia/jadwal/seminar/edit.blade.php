@extends('layouts.app')

@section('title', 'Edit Jadwal ' . ucfirst($jadwal->jenis_acara))

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Edit Jadwal {{ ucfirst($jadwal->jenis_acara) }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('panitia.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('jadwal.' . $jadwal->jenis_acara . '.index') }}">Jadwal {{ ucfirst($jadwal->jenis_acara) }}</a></div>
                <div class="breadcrumb-item active">Edit</div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('jadwal.update', $jadwal->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="tanggal">Tanggal</label>
                <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                       value="{{ old('tanggal', $jadwal->tanggal) }}" required>
                @error('tanggal')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="jam_mulai">Jam Mulai</label>
                <input type="time" name="jam_mulai" class="form-control @error('jam_mulai') is-invalid @enderror"
                       value="{{ old('jam_mulai', $jadwal->jam_mulai) }}" required>
                @error('jam_mulai')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="jam_selesai">Jam Selesai (Opsional)</label>
                <input type="time" name="jam_selesai" class="form-control @error('jam_selesai') is-invalid @enderror"
                       value="{{ old('jam_selesai', $jadwal->jam_selesai) }}">
                @error('jam_selesai')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="ruangan">Ruangan</label>
                <input type="text" name="ruangan" class="form-control @error('ruangan') is-invalid @enderror"
                       value="{{ old('ruangan', $jadwal->ruangan) }}" required>
                @error('ruangan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            @if ($jadwal->jenis_acara !== 'yudisium')
                <div class="form-group">
                    <label for="penguji_1_id">Penguji 1</label>
                    <select name="penguji_1_id" class="form-control @error('penguji_1_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Penguji 1 --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('penguji_1_id', $jadwal->penguji_1_id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('penguji_1_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="penguji_2_id">Penguji 2 (Opsional)</label>
                    <select name="penguji_2_id" class="form-control @error('penguji_2_id') is-invalid @enderror">
                        <option value="">-- Pilih Penguji 2 --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('penguji_2_id', $jadwal->penguji_2_id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('penguji_2_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="penguji_3_id">Penguji 3 (Opsional)</label>
                    <select name="penguji_3_id" class="form-control @error('penguji_3_id') is-invalid @enderror">
                        <option value="">-- Pilih Penguji 3 --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('penguji_3_id', $jadwal->penguji_3_id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('penguji_3_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            @endif

            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary">Update Jadwal</button>
                <a href="{{ route('jadwal.' . $jadwal->jenis_acara . '.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </section>
</div>
@endsection
