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

        {{-- Tabel Mahasiswa Belum Dijadwalkan Sidang --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Mahasiswa Belum Dijadwalkan Sidang</h4>
                <div>
                    <a href="{{ route('panitia.jadwal.export', ['jenis' => $jenis]) }}" class="btn btn-success btn-sm"><i class="fas fa-file-export"></i> Export</a>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="text-center">
                        <tr>
                            <th>No</th>
                            <th>Mahasiswa</th>
                            <th>Judul TA</th>
                            <th>Pembimbing 1</th>
                            <th>Pembimbing 2</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengajuanBelumTerjadwal as $index => $pengajuan)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $pengajuan->kelompok->anggota->pluck('nama_mhs')->join(', ') }}</td>
                            <td>{{ $pengajuan->judul_ta }}</td>
                            <td>{{ $pengajuan->dosen1->name ?? '-' }}</td>
                            <td>{{ $pengajuan->dosen2->name ?? '-' }}</td>
                            <td>
                                <a href="{{ route('panitia.jadwal.create', ['jenis' => $jenis]) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus"></i> Input
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center">Semua pengajuan sudah dijadwalkan sidang</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tabel Mahasiswa Sudah Dijadwalkan Sidang --}}
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Mahasiswa Sudah Dijadwalkan Sidang</h4>
                <div>
                    <a href="{{ route('panitia.jadwal.import.form', ['jenis' => $jenis]) }}" class="btn btn-success btn-sm"><i class="fas fa-file-import"></i> Import</a>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="text-center">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Ruangan</th>
                            <th>Mahasiswa</th>
                            <th>Judul TA</th>
                            <th>Pembimbing 1</th>
                            <th>Pembimbing 2</th>
                            <th>Penguji 1</th>
                            <th>Penguji 2</th>
                            <th>Penguji 3</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwals as $index => $jadwal)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ $jadwal->jam_selesai ? \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') : '-' }}</td>
                            <td>{{ $jadwal->ruangan }}</td>
                            <td>{{ $jadwal->pengajuan?->kelompok?->anggota->pluck('nama_mhs')->join(', ') ?? '-' }}</td>
                            <td>{{ $jadwal->pengajuan?->judul_ta ?? '-' }}</td>
                            <td>{{ $jadwal->pengajuan?->dosen1?->name ?? '-' }}</td>
                            <td>{{ $jadwal->pengajuan?->dosen2?->name ?? '-' }}</td>
                            <td>{{ $jadwal->penguji1?->name ?? '-' }}</td>
                            <td>{{ $jadwal->penguji2?->name ?? '-' }}</td>
                            <td>{{ $jadwal->penguji3?->name ?? '-' }}</td>
                            <td class="text-center">
                                <a href="{{ route('panitia.jadwal.edit', $jadwal->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="12" class="text-center">Belum ada jadwal sidang</td></tr>
                        @endforelse
                    </tbody>
                </table>
<a href="{{ route('panitia.jadwal.sidang.export') }}" class="btn btn-success">
    <i class="fas fa-file-export"></i> Export Jadwal
</a>

            </div>
        </div>

    </section>
</div>
@endsection
