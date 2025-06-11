@extends('layouts.app')

@section('title', 'Berkas Sidang - Final')

@push('style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endpush

@section('main')
<div class="main-content">
    <section class="section">
@if (session('success'))
    <div class="alert alert-success alert-dismissible show fade">
        <div class="alert-body">
            <button class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
            {{ session('success') }}
        </div>
    </div>
@endif

        <div class="section-header">
            <h1>Berkas Sidang - Final</h1>
        </div>

        <div class="table-responsive">
            <table id="table-sidang-final" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Mahasiswa</th>
                        <th>NIM</th>
                        <th>Prodi</th>
                        <th>Laporan Akhir (PDF)</th>
                        <th>Word</th>
                        <th>Konsultasi</th>
                        <th>Berita Acara</th>
                        <th>Buku Manual</th>
                        <th>Pengesahan</th>
                        <th>Status</th>
                        <th>Catatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
               <tbody>
@forelse ($sidangList as $sidangIndex => $sidang)
<tr>
    <td>{{ $sidangIndex + 1 }}</td>

    {{-- Nama Mahasiswa --}}
    <td>
        @foreach ($sidang->kelompok->anggota as $anggota)
            <div>{{ $anggota->nama_mhs }}</div>
        @endforeach
    </td>

    {{-- NIM --}}
    <td>
        @foreach ($sidang->kelompok->anggota as $anggota)
            <div>{{ $anggota->nim_mhs }}</div>
        @endforeach
    </td>

    {{-- Prodi --}}
    <td>
        {{ $sidang->kelompok->anggota->first()->prodi->nama_prodi ?? '-' }}
    </td>

    {{-- Laporan Akhir (PDF) --}}
    <td>
        @if ($sidang->laporan_akhir_pdf)
            <a href="{{ asset('storage/' . $sidang->laporan_akhir_pdf) }}" target="_blank">Lihat</a>
        @else
            <span class="text-muted">Belum</span>
        @endif
    </td>

    {{-- Word --}}
    <td>
        @if ($sidang->laporan_akhir_word)
            <a href="{{ asset('storage/' . $sidang->laporan_akhir_word) }}" target="_blank">Lihat</a>
        @else
            <span class="text-muted">Belum</span>
        @endif
    </td>

    {{-- Konsultasi --}}
    <td>
        @if ($sidang->lembar_konsultasi)
            <a href="{{ asset('storage/' . $sidang->lembar_konsultasi) }}" target="_blank">Lihat</a>
        @else
            <span class="text-muted">Belum</span>
        @endif
    </td>

    {{-- Berita Acara --}}
    <td>
        @if ($sidang->berita_acara)
            <a href="{{ asset('storage/' . $sidang->berita_acara) }}" target="_blank">Lihat</a>
        @else
            <span class="text-muted">Belum</span>
        @endif
    </td>

    {{-- Buku Manual --}}
    <td>
        @if ($sidang->buku_manual)
            <a href="{{ asset('storage/' . $sidang->buku_manual) }}" target="_blank">Lihat</a>
        @else
            <span class="text-muted">Belum</span>
        @endif
    </td>

    {{-- Pengesahan --}}
    <td>
        @if ($sidang->halaman_pengesahan)
            <a href="{{ asset('storage/' . $sidang->halaman_pengesahan) }}" target="_blank">Lihat</a>
        @else
            <span class="text-muted">Belum</span>
        @endif
    </td>

    {{-- Status --}}
    <td>
        @if ($sidang->status_final == 'Disetujui')
            <span class="badge badge-success">Disetujui</span>
        @elseif ($sidang->status_final == 'Ditolak')
            <span class="badge badge-danger">Ditolak</span>
        @else
            <span class="badge badge-secondary">Menunggu</span>
        @endif
    </td>

    {{-- Catatan --}}
    <td>
        <form action="{{ route('sidang.final.submit', $sidang->id_sidang) }}" method="POST" class="d-flex align-items-center gap-2">
            @csrf
            <textarea name="catatan_final" class="form-control me-2" rows="1" placeholder="Catatan (opsional)" style="width: 100%;">{{ $sidang->catatan_final ?? '' }}</textarea>
    </td>

    {{-- Aksi --}}
    <td class="d-flex gap-1 align-items-center">
            <button name="status_final" value="Disetujui" class="btn btn-success btn-sm">ACC</button>
            <button name="status_final" value="Ditolak" class="btn btn-danger btn-sm">Tolak</button>
        </form>
    </td>
</tr>
@empty
<tr>
    <td colspan="13" class="text-center text-danger">Belum ada data.</td>
</tr>
@endforelse
</tbody>
            </table>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('#table-sidang-final').DataTable({
            "language": {
                "search": "Cari Mahasiswa / NIM / Prodi:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Data tidak ditemukan",
                "info": "Menampilkan _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data",
                "infoFiltered": "(disaring dari total _MAX_ data)"
            },
            "pageLength": 10
        });
    });
</script>
@endpush
