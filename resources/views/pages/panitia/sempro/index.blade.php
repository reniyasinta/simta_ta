@extends('layouts.app')

@section('title', 'Berkas SEMPRO')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Berkas Sempro</h1>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Daftar Berkas Sempro</h4>
                </div>
                <div class="card-body">
                    <table id="table-sempro" class="table table-bordered table-striped table-hover">
                        <thead class="thead-dark">
                    <tr>
                    <th class="text-center" style="width: 50px;">No</th>
                    <th class="text-center">Anggota Kelompok</th>
                    <th class="text-center">Proposal TA</th>
                    <th class="text-center">Form Persetujuan</th>
                    <th class="text-center">Berita Acara</th>
                </tr>
                </thead>
                <tbody>
                    @forelse ($pengajuanList as $index => $pengajuan)
                        @php
                            $sempro = $semproList->where('id_ajuan', $pengajuan->id_ajuan)->first();
                        @endphp
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                <strong>Anggota:</strong>
                                <ul style="padding-left: 16px;">
                                    @if($pengajuan->kelompok && $pengajuan->kelompok->anggota1)
                                        <li>{{ $pengajuan->kelompok->anggota1->mahasiswa->nama_mhs ?? '-' }} ({{ $pengajuan->kelompok->anggota1->mahasiswa->nim_mhs ?? '-' }})</li>
                                    @endif
                                    @if($pengajuan->kelompok && $pengajuan->kelompok->anggota2)
                                        <li>{{ $pengajuan->kelompok->anggota2->mahasiswa->nama_mhs ?? '-' }} ({{ $pengajuan->kelompok->anggota2->mahasiswa->nim_mhs ?? '-' }})</li>
                                    @endif
                                    @if($pengajuan->kelompok && $pengajuan->kelompok->anggota3)
                                        <li>{{ $pengajuan->kelompok->anggota3->mahasiswa->nama_mhs ?? '-' }} ({{ $pengajuan->kelompok->anggota3->mahasiswa->nim_mhs ?? '-' }})</li>
                                    @endif
                                </ul>
                            </td>
                            <td class="text-center">
                                @if ($sempro && $sempro->proposal_ta)
                                    <a href="{{ asset($sempro->proposal_ta) }}" target="_blank" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                @else
                                    <span class="badge badge-secondary">Belum Upload</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($sempro && $sempro->form_persetujuan_sempro)
                                    <a href="{{ asset($sempro->form_persetujuan_sempro) }}" target="_blank" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                @else
                                    <span class="badge badge-secondary">Belum Upload</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($sempro && $sempro->berita_acara_sempro)
                                    <a href="{{ asset($sempro->berita_acara_sempro) }}" target="_blank" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                @else
                                    <span class="badge badge-secondary">Belum Upload</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-danger">Belum ada pengajuan Diterima.</td>
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
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endpush

@push('scripts')
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#table-sempro').DataTable({
                "language": {
                    "search": "Cari Mahasiswa / NIM:",
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
