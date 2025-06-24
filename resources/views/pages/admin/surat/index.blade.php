@extends('layouts.app')

@section('title', 'Surat Penelitian Mahasiswa')

@push('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
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

        {{-- Card Putih --}}
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h4 class="mb-0">Data Pengajuan Surat</h4>
            </div>

            <div class="card-body">

                {{-- Filter Perihal & Prodi --}}
                @php
                    $allProdi = \App\Models\Prodi::pluck('nama_prodi', 'id');
                @endphp

                <form method="GET" action="{{ route('admin.surat.index') }}" class="mb-4">
                    <div class="form-row">
                        <div class="col-md-3 mb-2">
                            <select name="perihal" id="perihal" class="form-control" onchange="this.form.submit()">
                                <option value="">-- Semua Perihal --</option>
                                <option value="Studi Pendahuluan" {{ request('perihal') == 'Studi Pendahuluan' ? 'selected' : '' }}>Studi Pendahuluan</option>
                                <option value="Pengantar Penelitian" {{ request('perihal') == 'Pengantar Penelitian' ? 'selected' : '' }}>Pengantar Penelitian</option>
                                <option value="Permintaan Data" {{ request('perihal') == 'Permintaan Data' ? 'selected' : '' }}>Permintaan Data</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <select name="prodi" id="prodi" class="form-control" onchange="this.form.submit()">
                                <option value="">-- Semua Prodi --</option>
                                @foreach($allProdi as $id => $nama)
                                    <option value="{{ $id }}" {{ request('prodi') == $id ? 'selected' : '' }}>{{ $nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>

                {{-- Tabel Surat --}}
                <div class="table-responsive">
                    <table id="table-surat" class="table table-bordered table-striped text-center">
                        <thead class="text-center">
                            <tr>
                                <th>Mahasiswa</th>
                                <th>Prodi</th>
                                <th>Judul TA</th>
                                <th>Tujuan</th>
                                <th>Perihal</th>
                                <th>Dosen Pembimbing 1</th>
                                <th>Dosen Pembimbing 2</th>
                                <th>Status</th>
                                <th>Preview</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $filteredSurat = $daftarSurat->filter(function ($surat) {
                                    $selectedProdi = request('prodi');
                                    $mhsProdi = $surat->mahasiswa?->id_prodi;
                                    return !$selectedProdi || ($mhsProdi == $selectedProdi);
                                })->values();
                            @endphp

                            @forelse($filteredSurat as $surat)
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
                                    <td>
                                        {{ $surat->mahasiswa->prodi->nama_prodi ?? '-' }}
                                    </td>
                                    <td>{{ $surat->judul_ta }}</td>
                                    <td>{{ $surat->tujuan }}</td>
                                    <td>{{ $surat->perihal }}</td>
                                    <td>
                                        {{ $surat->mahasiswa?->pengajuanDiterima?->dosen1?->dosen?->nama_dosen ?? '-' }}
                                    </td>
                                    <td>
                                        {{ $surat->mahasiswa?->pengajuanDiterima?->dosen2?->dosen?->nama_dosen ?? '-' }}
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
                                    <td colspan="10" class="text-center">Belum ada pengajuan surat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </section>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#table-surat').DataTable({
            "language": {
                "search": "Cari Nama / Judul / Tujuan / Dospem:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Data tidak ditemukan",
                "info": "Menampilkan _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data",
                "infoFiltered": "(filtered from _MAX_ total records)"
            },
            "pageLength": 10
        });
    });
</script>
@endpush
