@extends('layouts.app')

@section('main')
    <div class="container-fluid py-5">
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>Input Jadwal Seminar</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="{{ url('home') }}">Dashboard</a></div>
                        <div class="breadcrumb-item">Jadwal</div>
                        <div class="breadcrumb-item">Input Manual</div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @elseif(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('jadwal.store') }}" method="POST">
                    @csrf

                    <div class="form-group mb-3">
                        <label>Tanggal Mulai</label>
                        <input type="datetime-local" name="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror" required>
                        @error('tanggal_mulai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label>Tanggal Selesai</label>
                        <input type="datetime-local" name="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror">
                        @error('tanggal_selesai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label>Tempat</label>
                        <input type="text" name="tempat" class="form-control @error('tempat') is-invalid @enderror" placeholder="Tempat Acara" required>
                        @error('tempat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label>Jenis Acara</label>
                        <select name="jenis_acara" class="form-control @error('jenis_acara') is-invalid @enderror" required>
                            <option value="sosialisasi">Sosialisasi</option>
                            <option value="seminar_proposal">Seminar Proposal</option>
                            <option value="sidang_ta">Sidang TA</option>
                        </select>
                        @error('jenis_acara')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label>Judul TA</label>
                        <input type="text" name="judul_ta" class="form-control @error('judul_ta') is-invalid @enderror" placeholder="Judul TA" required>
                        @error('judul_ta')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label>NIM</label>
                        <input type="text" name="nim" class="form-control @error('nim') is-invalid @enderror" placeholder="NIM Mahasiswa" required>
                        @error('nim')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label>Nama Mahasiswa</label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" placeholder="Nama Mahasiswa" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label>Program Studi</label>
                        <input type="text" name="prodi" class="form-control @error('prodi') is-invalid @enderror" placeholder="Program Studi" required>
                        @error('prodi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label>Kelas</label>
                        <input type="text" name="kelas" class="form-control @error('kelas') is-invalid @enderror" placeholder="Kelas" required>
                        @error('kelas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label>Pembimbing 1</label>
                        <input type="text" name="pembimbing_1" class="form-control @error('pembimbing_1') is-invalid @enderror" placeholder="Pembimbing 1" required>
                        @error('pembimbing_1')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label>Pembimbing 2 (Opsional)</label>
                        <input type="text" name="pembimbing_2" class="form-control @error('pembimbing_2') is-invalid @enderror" placeholder="Pembimbing 2">
                        @error('pembimbing_2')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label>Penguji 1</label>
                        <select name="penguji_1_id" class="form-control @error('penguji_1_id') is-invalid @enderror" required>
                            <option value="">Pilih Penguji 1</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('penguji_1_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label>Penguji 2 (Opsional)</label>
                        <select name="penguji_2_id" class="form-control @error('penguji_2_id') is-invalid @enderror">
                            <option value="">Pilih Penguji 2</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('penguji_2_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label>Penguji 3 (Opsional)</label>
                        <select name="penguji_3_id" class="form-control @error('penguji_3_id') is-invalid @enderror">
                            <option value="">Pilih Penguji 3</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('penguji_3_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
                        <a href="{{ route('jadwal.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>

            </section>
        </div>
    </div>
@endsection
