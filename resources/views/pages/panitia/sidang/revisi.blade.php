@extends('layouts.app')

@section('title', 'Berkas Sidang - Revisi')

@push('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endpush

@section('main')
    <div class="main-content">
<section class="section">
    <div class="section-header">
        <h1>Berkas Sidang - Revisi</h1>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Daftar Revisi Laporan Sidang</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table-sidang-revisi" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Mahasiswa</th>
                            <th>NIM</th>
                            <th>Prodi</th>
                            <th>Revisi Laporan</th>
                            <th>Status Revisi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sidangList as $index => $sidang)
                            <tr>
                                <td>{{ $index + 1 }}</td>
<td>
    @foreach (['anggota1', 'anggota2', 'anggota3'] as $pos)
        @if($sidang->kelompok && $sidang->kelompok->$pos && $sidang->kelompok->$pos->mahasiswa)
            <div>{{ $sidang->kelompok->$pos->mahasiswa->nama_mhs ?? '-' }}</div>
        @endif
    @endforeach
</td>
<td>
    @foreach (['anggota1', 'anggota2', 'anggota3'] as $pos)
        @if($sidang->kelompok && $sidang->kelompok->$pos && $sidang->kelompok->$pos->mahasiswa)
            <div>{{ $sidang->kelompok->$pos->mahasiswa->nim_mhs ?? '-' }}</div>
        @endif
    @endforeach
</td>

                                <td>{{ $sidang->mahasiswa->prodi->nama_prodi ?? '-' }}</td>
                                <td>
                                    @if ($sidang->revisi_laporan)
                                        <a href="{{ asset($sidang->revisi_laporan) }}" target="_blank" class="btn btn-sm btn-info">Lihat</a>
                                    @else
                                        <span class="text-muted">Belum Upload</span>
                                    @endif
                                </td>
                                <td>
                                    <div><strong>Penguji 1:</strong> {{ $sidang->status_revisi_penguji_1 ?? '-' }}</div>
                                    <div><strong>Penguji 2:</strong> {{ $sidang->status_revisi_penguji_2 ?? '-' }}</div>
                                    <div><strong>Penguji 3:</strong> {{ $sidang->status_revisi_penguji_3 ?? '-' }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-danger">Belum ada data.</td>
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
            $('#table-sidang-revisi').DataTable({
                "language": {
                    "search": "Cari Mahasiswa / NIM / Prodi:",
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
