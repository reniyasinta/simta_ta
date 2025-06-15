@extends('layouts.app')

@section('title', 'Input Jadwal ' . ucfirst($jenis))

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Input Jadwal {{ ucfirst($jenis) }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('panitia.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('panitia.jadwal.jenis.index', ['jenis' => $jenis]) }}">Jadwal {{ ucfirst($jenis) }}</a></div>
                <div class="breadcrumb-item active">Input</div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('panitia.jadwal.store') }}" method="POST">
            @csrf
            <input type="hidden" name="jenis_acara" value="{{ $jenis }}">

            @if ($jenis !== 'yudisium')
                <div class="form-group">
                    <label for="id_ajuan">Pilih Pengajuan (Mahasiswa)</label>
                    <select name="id_ajuan" class="form-control" required>
                        <option value="">-- Pilih Pengajuan --</option>
                        @foreach($pengajuans as $pengajuan)
                            <option value="{{ $pengajuan->id_ajuan }}">
                                {{ $pengajuan->kelompok->anggota->pluck('nama_mhs')->join(', ') }} - {{ $pengajuan->judul_ta }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="form-group">
                <label for="tanggal">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="jam_mulai">Jam Mulai</label>
                <input type="time" name="jam_mulai" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="jam_selesai">Jam Selesai (Opsional)</label>
                <input type="time" name="jam_selesai" class="form-control">
            </div>

            <div class="form-group">
                <label for="ruangan">Ruangan</label>
                <input type="text" name="ruangan" class="form-control" required>
            </div>

            @if ($jenis !== 'yudisium')
                <div class="form-group">
                    <label for="penguji_1_id">Penguji 1</label>
                    <select name="penguji_1_id" class="form-control" required>
                        <option value="">-- Pilih Penguji 1 --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="penguji_2_id">Penguji 2 (Opsional)</label>
                    <select name="penguji_2_id" class="form-control">
                        <option value="">-- Pilih Penguji 2 --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="penguji_3_id">Penguji 3 (Opsional)</label>
                    <select name="penguji_3_id" class="form-control">
                        <option value="">-- Pilih Penguji 3 --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="text-end">
                <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
                <a href="{{ route('panitia.jadwal.jenis.index', ['jenis' => $jenis]) }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </section>
</div>
@endsection
