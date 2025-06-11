@extends('layouts.app')

@section('title', 'Pengajuan Mahasiswa')

@push('style')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endpush

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Daftar Penentuan Dosen Pembimbing 2</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('home') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Pengajuan Mahasiswa</div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table id="table-pengajuan" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kelompok</th>
                        <th>Judul</th>
                        <th>Dosen 1</th>
                        <th>Dosen 2</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuanList as $key => $p)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <ul>
                                    @if($p->kelompok && $p->kelompok->anggota && $p->kelompok->anggota->count())
                                        @foreach($p->kelompok->anggota as $mhs)
                                            <li>{{ $mhs->nama_mhs }} ({{ $mhs->nim_mhs }})</li>
                                        @endforeach
                                    @else
                                        <li><em>Tidak ada anggota</em></li>
                                    @endif
                                </ul>
                            </td>
                            <td>{{ $p->judul_ta }}</td>
                            <td>{{ $p->dosen1->dosen->nama_dosen ?? '-' }}</td>
                            <td>{{ $p->dosen2->dosen->nama_dosen ?? 'Belum ditetapkan' }}</td>
                            <td>
                                <a href="{{ route('panitia.pengajuan.edit', $p->id_ajuan) }}" class="btn btn-sm btn-primary">
                                    Tentukan Dosen 2
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada pengajuan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#table-pengajuan').DataTable({
            "language": {
                "search": "Cari Mahasiswa / NIM / Judul:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Data tidak ditemukan",
                "info": "Menampilkan _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data",
                "infoFiltered": "(difilter dari _MAX_ total data)"
            },
            "pageLength": 10
        });
    });
</script>
@endpush
