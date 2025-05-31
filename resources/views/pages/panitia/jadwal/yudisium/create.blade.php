@extends('layouts.app')

@section('title', 'Input Jadwal Yudisium')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Input Jadwal Yudisium</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('panitia.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('jadwal.yudisium.index') }}">Yudisium</a></div>
                <div class="breadcrumb-item active">Input</div>
            </div>
        </div>

        {{-- === FORM INPUT JADWAL YUDISIUM === --}}
        <div class="card">
            <div class="card-header"><h4>Form Input</h4></div>
            <div class="card-body">
                <form action="{{ route('jadwal.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="jenis_acara" value="yudisium">

                    <div class="form-group">
                        <label for="tanggal">Tanggal Yudisium</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control"
                               value="{{ old('tanggal') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="jam_mulai">Jam Mulai</label>
                        <input type="time" name="jam_mulai" id="jam_mulai" class="form-control"
                               value="{{ old('jam_mulai') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="jam_selesai">Jam Selesai (opsional)</label>
                        <input type="time" name="jam_selesai" id="jam_selesai" class="form-control"
                               value="{{ old('jam_selesai') }}">
                    </div>

                    <div class="form-group">
                        <label for="ruangan">Tempat</label>
                        <input type="text" name="ruangan" id="ruangan" class="form-control"
                               value="{{ old('ruangan') }}" required>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('jadwal.yudisium.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
