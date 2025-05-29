@extends('layouts.app')

@section('title', 'Surat Penelitian Mahasiswa')

@push('style')
<link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Daftar Pengajuan Surat Penelitian</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ url('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Surat</div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form method="GET" action="{{ route('admin.surat.index') }}">
                <div class="form-group row">
                    <div class="col-sm-3">
                        <select name="perihal" id="perihal" class="form-control" onchange="this.form.submit()">
                            <option value="">-- Semua Perihal --</option>
                            <option value="Studi Pendahuluan" {{ request('perihal') == 'Studi Pendahuluan' ? 'selected' : '' }}>Studi Pendahuluan</option>
                            <option value="Pengantar Penelitian" {{ request('perihal') == 'Pengantar Penelitian' ? 'selected' : '' }}>Pengantar Penelitian</option>
                            <option value="Permintaan Data" {{ request('perihal') == 'Permintaan Data' ? 'selected' : '' }}>Permintaan Data</option>
                        </select>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped mt-3">
                    <thead>
                        <tr>
                            <th>Mahasiswa</th>
                            <th>Judul TA</th>
                            <th>Tujuan</th>
                            <th>Perihal</th>
                            <th>Dosen Pembimbing</th>
                            <th>Status</th>
                            <th>Preview</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($daftarSurat as $surat)
                            <tr>
                                <td>
                                    @if($surat->mahasiswa && $surat->mahasiswa->kelompok)
                                        <ul class="mb-0">
                                            @foreach($surat->mahasiswa->kelompok->anggota as $anggota)
                                                <li>{{ $anggota->nama_mhs }} - {{ $anggota->nim_mhs }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <em>Tidak ada kelompok</em>
                                    @endif
                                </td>
                                <td>{{ $surat->judul_ta }}</td>
                                <td>{{ $surat->tujuan }}</td>
                                <td>{{ $surat->perihal }}</td>
                                <td>
                                    @php
                                        $pengajuan = $surat->mahasiswa?->pengajuanDiterima;
                                        $dospem1 = $pengajuan?->dosen1?->dosen?->nama_dosen;
                                    @endphp
                                    {{ $dospem1 ?? '-' }}
                                </td>
                                <td class="text-capitalize">{{ $surat->status ?? '-' }}</td>
                                <td>
                                    @if($surat->file_surat)
                                    <a href="{{ Storage::url($surat->file_surat) }}" target="_blank" class="text-success">Lihat</a>
                                    @else
                                        <span class="text-muted">Belum tersedia</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.surat.edit', $surat->id_surat) }}" class="btn btn-sm btn-primary">Upload</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Belum ada pengajuan surat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
@endsection
