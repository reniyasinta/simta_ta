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
                                <th class="text-center">Anggota Kelompok</th>
                                <th class="text-center">Prodi</th>
                                <th class="text-center">Laporan TA</th>
                                <th class="text-center">Lembar Konsultasi</th>
                                <th class="text-center">Status Draft</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sidangList as $index => $sidang)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <strong>Anggota:</strong>
                                        <ul style="padding-left: 16px;">
                                            @if($sidang->kelompok && $sidang->kelompok->anggota1)
                                                <li>{{ $sidang->kelompok->anggota1->mahasiswa->nama_mhs ?? '-' }} ({{ $sidang->kelompok->anggota1->mahasiswa->nim_mhs ?? '-' }})</li>
                                            @endif
                                            @if($sidang->kelompok && $sidang->kelompok->anggota2)
                                                <li>{{ $sidang->kelompok->anggota2->mahasiswa->nama_mhs ?? '-' }} ({{ $sidang->kelompok->anggota2->mahasiswa->nim_mhs ?? '-' }})</li>
                                            @endif
                                            @if($sidang->kelompok && $sidang->kelompok->anggota3)
                                                <li>{{ $sidang->kelompok->anggota3->mahasiswa->nama_mhs ?? '-' }} ({{ $sidang->kelompok->anggota3->mahasiswa->nim_mhs ?? '-' }})</li>
                                            @endif
                                        </ul>
                                    </td>
                                    <td class="text-center">
                                        {{ $sidang->mahasiswa->prodi->nama_prodi ?? '-' }}
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
                                    <td colspan="6" class="text-center text-danger">Belum ada data sidang.</td>
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
