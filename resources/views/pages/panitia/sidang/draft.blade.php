@extends('layouts.app')

@section('title', 'Berkas Sidang - Draft')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Berkas Sidang - Draft</h1>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Daftar Berkas Sidang Mahasiswa</h4>
                </div>
                <div class="card-body">
                    <table id="table-sidang" class="table table-bordered table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th class="text-center" style="width: 50px;">No</th>
                                <th class="text-center">Nama Mahasiswa</th>
                                <th class="text-center">NIM</th>
                                <th class="text-center">Prodi</th>
                                <th class="text-center">Laporan TA</th>
                                <th class="text-center">Form Persetujuan</th>
                                <th class="text-center">Lembar Konsultasi</th>
                                <th class="text-center">Status Draft</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sidangList as $index => $sidang)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        @foreach ($sidang->kelompok->anggota as $anggota)
                                            <div>{{ $anggota->nama_mhs ?? '-' }}</div>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($sidang->kelompok->anggota as $anggota)
                                            <div>{{ $anggota->nim_mhs ?? '-' }}</div>
                                        @endforeach
                                    </td>
                                    <td class="text-center">
                                        {{
                                            $sidang->kelompok->anggota->first()->prodi->nama_prodi
                                                ?? 'Tidak ada prodi'
                                        }}
                                    </td>
                                    <td class="text-center">
                                        @if ($sidang->laporan_TA)
                                            <a href="{{ asset($sidang->laporan_TA) }}" target="_blank" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                        @else
                                            <span class="badge badge-secondary">Belum Upload</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($sidang->from_persetujuan_sidang)
                                            <a href="{{ asset($sidang->from_persetujuan_sidang) }}" target="_blank" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                        @else
                                            <span class="badge badge-secondary">Belum Upload</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($sidang->lembar_konsultasi)
                                            <a href="{{ asset($sidang->lembar_konsultasi) }}" target="_blank" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                        @else
                                            <span class="badge badge-secondary">Belum Upload</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($sidang->status_draft_dosen1 === 'Disetujui' && $sidang->status_draft_dosen2 === 'Disetujui')
                                            <span class="badge badge-success">Disetujui</span>
                                        @else
                                            <span class="badge badge-warning">Menunggu / Revisi</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-danger">Belum ada data sidang.</td>
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

@push('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('#table-sidang').DataTable({
            "language": {
                "search": "Cari Mahasiswa / NIM / Prodi:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Data tidak ditemukan",
                "info": "Menampilkan _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data",
                "infoFiltered": "(disaring dari _MAX_ total data)"
            },
            "pageLength": 10
        });
    });
</script>
@endpush
