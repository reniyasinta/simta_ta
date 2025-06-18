@extends('layouts.app')

@section('title', 'Tentukan Dosen Pembimbing 2')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Tentukan Dosen Pembimbing 2</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('panitia.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('panitia.pengajuan.index') }}">Pengajuan</a></div>
                <div class="breadcrumb-item active">Tentukan Dosen 2</div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('panitia.pengajuan.update', $pengajuan->id_ajuan) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card">
                <div class="card-header"><h4>Form Pemilihan Dosen 2</h4></div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Kelompok</label>
                        <div class="form-control" readonly>
                            {{ $pengajuan->kelompok->anggota->pluck('nama_mhs')->implode(', ') ?? '-' }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Judul TA</label>
                        <input type="text" class="form-control" value="{{ $pengajuan->judul_ta }}" readonly>
                    </div>

                    <div class="form-group">
                        <label>Dosen Pembimbing 1</label>
                        <input type="text" class="form-control" value="{{ $pengajuan->dosen1->dosen->nama_dosen ?? '-' }}" readonly>
                    </div>

<div class="form-group mb-3">
    <label for="id_dosen2">Pilih Dosen Pembimbing 2</label>
    <select name="id_dosen2" class="form-control selectric" required>
        <option value="">-- Pilih Dosen --</option>
        @foreach($dosenList as $dosen)
            @php
                $label = $dosen->name;

                if ($dosen->kuota_total == 0) {
                    $isDisabled = true;
                    $label .= ' (Belum ditentukan)';
                } elseif ($dosen->kuota_terpakai >= $dosen->kuota_total) {
                    $isDisabled = true;
                    $label .= ' (Kuota Penuh)';
                } else {
                    $isDisabled = false;
                    $label .= ' (' . $dosen->kuota_terpakai . '/' . $dosen->kuota_total . ')';
                }
            @endphp

            <option value="{{ $dosen->id }}"
                {{ $pengajuan->id_dosen2 == $dosen->id ? 'selected' : '' }}
                {{ $isDisabled ? 'disabled' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
</div>


                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('panitia.pengajuan.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </section>
</div>
@endsection
