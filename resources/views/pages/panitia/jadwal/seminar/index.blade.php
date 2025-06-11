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
            <a href="{{ route('jadwal.create', ['jenis' => $jenis]) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah
            </a>
            <a href="{{ route('jadwal.import.form', ['jenis' => $jenis]) }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-import"></i> Import
            </a>
            <a href="{{ route('jadwal.template', ['jenis' => $jenis]) }}" class="btn btn-info btn-sm">
                <i class="fas fa-download"></i> Template
            </a>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Ruangan</th>
                        <th>Nama Mahasiswa</th>
                        <th>Program Studi</th>
                        <th>Judul TA</th>
                        <th>Pembimbing 1</th>
                        <th>Pembimbing 2</th>
                        <th>Penguji 1</th>
                        <th>Penguji 2</th>
                        <th>Penguji 3</th>
                        <th style="width: 90px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($jadwals as $index => $jadwal)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}
                            -
                            {{ $jadwal->jam_selesai ? \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') : '-' }}
                        </td>
                        <td>{{ $jadwal->ruangan }}</td>
                        <td>{{ $jadwal->pengajuan?->kelompok?->anggota->pluck('nama_mhs')->join(', ') ?? $jadwal->nama }}</td>
                        <td>{{ $jadwal->pengajuan?->kelompok?->anggota->first()->prodi->nama_prodi ?? '-' }}</td>
                        <td>{{ $jadwal->pengajuan?->judul_ta ?? $jadwal->judul_ta }}</td>
                        <td>{{ $jadwal->pengajuan?->dosen1->name ?? $jadwal->pembimbing_1 }}</td>
                        <td>{{ $jadwal->pengajuan?->dosen2->name ?? ($jadwal->pembimbing_2 ?? '-') }}</td>
                        <td>{{ $jadwal->penguji1->name ?? '-' }}</td>
                        <td>{{ $jadwal->penguji2->name ?? '-' }}</td>
                        <td>{{ $jadwal->penguji3->name ?? '-' }}</td>
                        <td class="text-center" style="white-space: nowrap;">
                            <a href="{{ route('jadwal.edit', $jadwal->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('jadwal.destroy', $jadwal->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus jadwal ini?')" title="Hapus">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    @forelse ($pengajuans as $index => $pengajuan)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>
                                @foreach ($pengajuan->kelompok->anggota as $anggota)
                                    <div>{{ $anggota->nama_mhs ?? '-' }}</div>
                                @endforeach
                            </td>
                            <td>{{ $pengajuan->kelompok->anggota->first()->prodi->nama_prodi ?? '-' }}</td>
                            <td>{{ $pengajuan->judul_ta ?? $pengajuan->judul }}</td>
                            <td>{{ $pengajuan->dosen1->name ?? '-' }}</td>
                            <td>{{ $pengajuan->dosen2->name ?? '-' }}</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td class="text-center" style="white-space: nowrap;">
                                <a href="{{ route('jadwal.create', ['jenis' => $jenis, 'pengajuan_id' => $pengajuan->id]) }}"
                                   class="btn btn-sm btn-primary" title="Input Jadwal">
                                    <i class="fas fa-plus"></i> Input
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="text-center">Belum ada jadwal {{ $jenis }} dan belum ada pengajuan yang lengkap.</td>
                        </tr>
                    @endforelse
                @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
