@extends('layouts.app')

@section('title', 'Surat Penelitian Mahasiswa')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Riwayat Pengajuan Surat Pendukung</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ url('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Surat</div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="d-flex justify-content-end mb-3">
                <a href="{{ route('mahasiswa.surat.create') }}" class="btn btn-primary">Ajukan Baru</a>
            </div>
            <form method="GET" action="{{ route('mahasiswa.surat.index') }}">
                <div class="form-group row">
                    <div class="col-sm-2">
                        <select name="perihal" id="perihal" class="form-control" onchange="this.form.submit()">
                            <option value="">-- Cari Perihal --</option>
                            <option value="Semua Perihal" {{ request('perihal') == 'Semua Perihal' ? 'selected' : '' }}>Semua Perihal</option>
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
                            <th>Anggota Kelompok</th>
                            <th>Judul TA</th>
                            <th>Tujuan</th>
                            <th>Perihal</th>
                            <th>Dosen Pembimbing</th>
                            <th>Status</th>
                            <th>Surat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suratList as $surat)
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
                                <td class="text-capitalize">{{ $surat->status }}</td>
                                <td>
                                    @if($surat->file_surat)
                                    <a href="{{ route('mahasiswa.surat.download', $surat->id_surat) }}" class="text-success">Download</a>
                                    @else
                                        <span class="text-muted">Belum tersedia</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada pengajuan surat</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </section>
    </div>
</div>
@endsection
