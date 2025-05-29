@extends('layouts.app')

@section('title', 'Daftar Jadwal ' . ucfirst($jenis))

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Daftar Jadwal {{ ucfirst($jenis) }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('panitia.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item active">Jadwal {{ ucfirst($jenis) }}</div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="d-flex justify-content-end mb-3" style="gap: 10px;">
            <a href="{{ route('jadwal.create', ['jenis' => $jenis]) }}" class="btn btn-primary">+ Tambah Jadwal</a>
            <a href="{{ route('jadwal.import.form', ['jenis' => $jenis]) }}" class="btn btn-primary">Import Jadwal</a>
            <a href="{{ route('jadwal.template', ['jenis' => $jenis]) }}" class="btn btn-primary">Download Template</a>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul TA</th>
                        <th>Nama Mahasiswa</th>
                        <th>Program Studi</th>
                        <th>Kelas</th>
                        <th>Jenis Acara</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Tempat</th>
                        <th>Pembimbing 1</th>
                        <th>Pembimbing 2</th>
                        <th>Penguji 1</th>
                        <th>Penguji 2</th>
                        <th>Penguji 3</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jadwals as $index => $jadwal)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $jadwal->judul_ta }}</td>
                            <td>{{ $jadwal->nama }}</td>
                            <td>{{ $jadwal->prodi }}</td>
                            <td>{{ $jadwal->kelas }}</td>
                            <td>{{ ucfirst($jadwal->jenis_acara) }}</td>
                            <td>{{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d M Y, H:i') }}</td>
                            <td>{{ $jadwal->tanggal_selesai ? \Carbon\Carbon::parse($jadwal->tanggal_selesai)->format('d M Y, H:i') : '-' }}</td>
                            <td>{{ $jadwal->tempat }}</td>
                            <td>{{ $jadwal->pembimbing_1 }}</td>
                            <td>{{ $jadwal->pembimbing_2 ?? '-' }}</td>
                            <td>{{ $jadwal->penguji1->name ?? '-' }}</td>
                            <td>{{ $jadwal->penguji2->name ?? '-' }}</td>
                            <td>{{ $jadwal->penguji3->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="14" class="text-center">Belum ada jadwal {{ $jenis }}.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
