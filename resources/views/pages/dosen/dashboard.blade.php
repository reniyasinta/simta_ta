@extends('layouts.app')
@section('title', 'Dashboard Dosen')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Selamat datang, {{ Auth::user()->dosen?->nama_dosen ?? 'Dosen Tidak Ditemukan' }}</h1>
        </div>

        <div class="row">

            {{-- Kartu Kuota Bimbingan --}}
            <div class="col-md-4">
                <div class="card card-statistic-1 shadow">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Kuota Bimbingan</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalBimbingan }} / {{ $kuota }} Mahasiswa
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kartu Jumlah Jadwal --}}
            <div class="col-md-4">
                <div class="card card-statistic-1 shadow">
                    <div class="card-icon bg-success">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Jadwal Ujian</h4>
                        </div>
                        <div class="card-body">
                            {{ $jadwals->count() }} Jadwal
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Tabel Jadwal --}}
        <div class="card mt-4">
            <div class="card-header">
                <h4>Jadwal Anda sebagai Penguji</h4>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Ujian</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Tempat</th>
                            <th>Judul TA</th>
                            <th>Mahasiswa</th>
                            <th>Undangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jadwals as $jadwal)
                            <tr>
                                <td>{{ ucfirst($jadwal->jenis_acara) }}</td>
                                <td>{{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('l, d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</td>
                                <td>{{ $jadwal->ruangan }}</td>
                                <td>{{ $jadwal->judul_ta }}</td>
                                <td>{{ $jadwal->pengajuan?->kelompok?->anggota->pluck('nama_mhs')->implode(', ') ?? '-' }}</td>
                                <td>
                                    @php
                                        $undangan = \App\Models\Undangan::where('jadwal_id', $jadwal->id)
                                            ->where('penguji_id', Auth::id())
                                            ->where('jenis_acara', $jadwal->jenis_acara) // penting: supaya Seminar & Sidang beda
                                            ->first();
                                    @endphp

                                    @if ($undangan)
                                        <a href="{{ Storage::url($undangan->file_path) }}" target="_blank" class="btn btn-sm btn-info">Preview</a>
                                        <a href="{{ Storage::url($undangan->file_path) }}" download class="btn btn-sm btn-success">Download</a>
                                    @else
                                        <span class="text-muted">Belum ada undangan</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada jadwal.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </section>
</div>
@endsection
