@extends('layouts.app')

@section('title', 'Laporan Akhir')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Laporan Akhir</h1>
        </div>

        {{-- Notifikasi success --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Notifikasi error --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Card Tabel File --}}
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Daftar File Laporan Akhir</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">

                    {{-- Tombol Upload --}}
                    <div class="mb-3 d-flex justify-content-end">
                        <a href="{{ route('mahasiswa.sidang.final.create') }}" class="btn btn-primary">+ Upload Laporan Akhir</a>
                    </div>

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Jenis</th>
                                <th>Status</th>
                                <th style="width: 200px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $files = [
                                    'laporan_akhir_pdf' => 'Laporan Akhir (PDF)',
                                    'laporan_akhir_word' => 'Laporan Akhir (Word)',
                                    'lembar_konsultasi' => 'Lembar Konsultasi',
                                    'berita_acara' => 'Berita Acara',
                                    'buku_manual' => 'Buku Manual',
                                    'halaman_pengesahan' => 'Halaman Pengesahan',
                                    'link_drive_proyek' => 'Link Drive Proyek',
                                ];
                                $i = 1;
                            @endphp

                            @foreach ($files as $field => $label)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $label }}</td>
                                    <td>
                                        @if($sidang && $sidang->$field)
                                            <span class="badge bg-success text-white">Sudah Upload</span>
                                        @else
                                            <span class="badge bg-secondary text-white">Belum Upload</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($sidang && $sidang->$field)
                                            @if($field === 'link_drive_proyek')
                                                <a href="{{ $sidang->$field }}" target="_blank" class="btn btn-sm btn-secondary" title="Lihat Link Drive">
                                                    <i class="fas fa-link"></i>
                                                </a>
                                            @else
                                                <a href="{{ asset($sidang->$field) }}" target="_blank" class="btn btn-sm btn-secondary" title="Lihat File">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endif

                                            <a href="{{ route('mahasiswa.sidang.final.edit', $field) }}" class="btn btn-sm btn-info" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form action="{{ route('mahasiswa.sidang.final.delete', ['jenis' => $field]) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Anda yakin ingin menghapus file ini?')" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        {{-- Card Validasi Panitia --}}
        <div class="card mt-4">
            <div class="card-header">
                <h4 class="card-title">Status Validasi Panitia</h4>
            </div>
            <div class="card-body">
                @if($sidang)
                    @if($sidang->status_final === 'Disetujui')
                        <span class="badge bg-success text-white">✅ Disetujui Panitia</span>
                    @elseif($sidang->status_final === 'Ditolak')
                        <span class="badge bg-danger text-white">❌ Ditolak Panitia</span>
                        <div class="mt-2"><strong>Catatan Panitia:</strong> {{ $sidang->catatan_final ?? '-' }}</div>
                    @else
                        <span class="badge bg-secondary text-white">Menunggu Validasi Panitia</span>
                    @endif
                @else
                    <span class="badge bg-secondary text-white">Belum ada data sidang</span>
                @endif
            </div>
        </div>

    </section>
</div>
@endsection
