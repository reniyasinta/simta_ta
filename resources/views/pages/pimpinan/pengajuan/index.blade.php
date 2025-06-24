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
                    <form method="GET" action="{{ route('pimpinan.pengajuan.index') }}" class="mb-3 w-50">
                        <label for="prodi_filter" class="form-label">Filter Prodi:</label>
                        <select name="prodi_id" id="prodi_filter" class="form-select" onchange="this.form.submit()">
                            <option value="">-- Semua Prodi --</option>
                            @foreach($prodis as $prodi)
                                <option value="{{ $prodi->id }}" {{ request('prodi_id') == $prodi->id ? 'selected' : '' }}>
                                    {{ $prodi->nama_prodi }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                    <table id="table-pengajuan" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kelompok</th>
                                <th>Prodi</th>
                                <th>Judul</th>
                                <th>Dosen Pembimbing 1</th>
                                <th>Dosen Pembimbing 2</th>
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
