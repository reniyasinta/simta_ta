@extends('layouts.app')

@section('title', 'Pengajuan Pembimbing')

@push('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endpush

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Daftar Dosen Pembimbing 1 dan 2 Mahasiswa</h1>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table-pengajuan" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kelompok</th>
                                <th>Prodi</th>
                                <th>Judul</th>
                                <th>Dosen 1</th>
                                <th>Dosen 2</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pengajuanList as $key => $p)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <ul class="mb-0">
                                            @foreach($p->kelompok->anggota as $mhs)
                                                <li>{{ $mhs->nama_mhs }} ({{ $mhs->nim_mhs }})</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>{{ $p->kelompok->anggota->first()->prodi->nama_prodi ?? '-' }}</td>
                                    <td>{{ $p->judul_ta }}</td>
                                    <td>{{ $p->dosen1->dosen->nama_dosen ?? '-' }}</td>
                                    <td>{{ $p->dosen2->dosen->nama_dosen ?? '-' }}</td>
                                </tr>
                            @endforeach
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
        $(document).ready(function () {
            $('#table-pengajuan').DataTable({
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
