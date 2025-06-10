@extends('layouts.app')

@section('title', 'Laporan Akhir')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Laporan Akhir</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-3 d-flex justify-content-end">
            <a href="{{ route('mahasiswa.sidang.final.create') }}" class="btn btn-primary">+ Upload Laporan Akhir</a>
        </div>

        <div class="table-responsive">
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
                                    <a href="{{ asset($sidang->$field) }}" target="_blank" class="btn btn-sm btn-secondary" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
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

                    {{-- Link Drive Proyek --}}
                    <tr>
                        <td>{{ $i }}</td>
                        <td>Link Drive Proyek</td>
                        <td>
                            @if($link_drive_proyek)
                                <span class="badge bg-success text-white">Tersedia</span>
                            @else
                                <span class="badge bg-secondary text-white">Belum tersedia</span>
                            @endif
                        </td>
                        <td>
                            @if($link_drive_proyek)
                                <a href="{{ $link_drive_proyek }}" target="_blank" class="btn btn-sm btn-success">Buka Drive</a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
