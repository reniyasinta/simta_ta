@extends('layouts.app')

@section('main')
<div class="container-fluid py-5">
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Daftar Jadwal Seminar</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('panitia.dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Jadwal</div>
                    <div class="breadcrumb-item">Daftar</div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

                <div class="d-flex justify-content-end mb-3" style="gap: 10px;">
                    <a href="{{ route('jadwal.create') }}" class="btn btn-primary">+ Tambah Jadwal</a>
                <a href="{{ route('jadwal.import') }}" class="btn btn-primary">Import Jadwal</a>
                    <a href="{{ route('template.jadwal') }}" class="btn btn-primary">Download Template Jadwal</a>
            </div>
                <div class="card-body table-responsive mb-4">
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
                                    <td>{{ $jadwal->tanggal_selesai ? \Carbon\Carbon::parse($jadwal->tanggal_selesai)->format('d M Y, H:i') : 'Belum selesai' }}</td>
                                    <td>{{ $jadwal->tempat }}</td>
                                    <td>{{ $jadwal->pembimbing_1 }}</td>
                                    <td>{{ $jadwal->pembimbing_2 ?? 'Tidak ada' }}</td>
                                    <td>{{ $jadwal->penguji_1_id ? $jadwal->penguji1->name : 'Belum ditentukan' }}</td>
                                    <td>{{ $jadwal->penguji_2_id ? $jadwal->penguji2->name : 'Belum ditentukan' }}</td>
                                    <td>{{ $jadwal->penguji_3_id ? $jadwal->penguji3->name : 'Belum ditentukan' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13" class="text-center">Belum ada jadwal</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="text-end mt-3">
                <a href="{{ route('jadwal.create') }}" class="btn btn-primary">Buat Jadwal</a>
            </div>
        </section>
    </div>
</div>
@endsection
