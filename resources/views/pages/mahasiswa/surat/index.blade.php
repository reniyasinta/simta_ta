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

        @php
            $mahasiswa = Auth::user()->mahasiswa;
            $pengajuan = \App\Models\PengajuanPembimbing::whereHas('kelompok', function ($q) use ($mahasiswa) {
                $q->whereHas('anggota', function ($qq) use ($mahasiswa) {
                    $qq->where('id_mhs', $mahasiswa->id_mhs);
                });
            })->where('status', 'Diterima')->latest()->first();

            $punyaDospem1 = $pengajuan?->id_dosen1 ? true : false;
            $punyaDospem2 = $pengajuan?->id_dosen2 ? true : false;
        @endphp
<div class="card">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <form method="GET" action="{{ route('mahasiswa.surat.index') }}" class="form-inline">
                <div class="form-group mb-0">
                    <select name="perihal" id="perihal" class="form-control" onchange="this.form.submit()">
                        <option value="">-- Cari Perihal --</option>
                        <option value="Semua Perihal" {{ request('perihal') == 'Semua Perihal' ? 'selected' : '' }}>Semua Perihal</option>
                        <option value="Studi Pendahuluan" {{ request('perihal') == 'Studi Pendahuluan' ? 'selected' : '' }}>Studi Pendahuluan</option>
                        <option value="Pengantar Penelitian" {{ request('perihal') == 'Pengantar Penelitian' ? 'selected' : '' }}>Pengantar Penelitian</option>
                        <option value="Permintaan Data" {{ request('perihal') == 'Permintaan Data' ? 'selected' : '' }}>Permintaan Data</option>
                    </select>
                </div>
            </form>

            @if($punyaDospem1 && $punyaDospem2)
                <a href="{{ route('mahasiswa.surat.create') }}" class="btn btn-primary">Ajukan Baru</a>
            @else
                <button class="btn btn-secondary" disabled>Ajukan Baru</button>
            @endif
        </div>


        <div class="table-responsive">
            <table class="table table-bordered table-striped mt-3">
                <thead>
                    <tr>
                        <th>Anggota Kelompok</th>
                        <th>Judul TA</th>
                        <th>Tujuan</th>
                        <th>Perihal</th>
                        <th>Status</th>
                        <th>Preview</th>
                        <th>Surat</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Cek apakah pengajuan dospem belum lengkap --}}
                    @if(!$punyaDospem1 && !$punyaDospem2)
                    <tr>
                        <td colspan="6" class="text-center py-3">
                            <strong>Anda belum memiliki Dosen Pembimbing 1 dan Dosen Pembimbing 2.</strong><br>
                            Silakan selesaikan proses pengajuan pembimbing terlebih dahulu sebelum mengajukan surat penelitian.
                        </td>
                    </tr>
                    @elseif($punyaDospem1 && !$punyaDospem2)
                    <tr>
                        <td colspan="6" class="text-center py-3">
                            <strong>Dosen Pembimbing 2 belum ditentukan.</strong><br>
                            Anda tidak dapat mengajukan berkas penelitian sebelum Dosen Pembimbing 2 ditentukan.
                        </td>
                    </tr>
                    @endif
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
                            <td class="text-capitalize">{{ $surat->status }}</td>
                             <td>
                            @if($surat->file_surat)
                                <a href="{{ Storage::url($surat->file_surat) }}" target="_blank">Lihat</a>
                            @else
                                <span class="text-muted">Belum tersedia</span>
                            @endif
                        </td>
                            <td>
                                @if($surat->file_surat)
                                    <a href="{{ route('mahasiswa.surat.download', $surat->id_surat) }}" target="_blank">Download</a>
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
@endsection
