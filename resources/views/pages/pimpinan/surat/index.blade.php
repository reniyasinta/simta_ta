@extends('layouts.app')

@section('title', 'Monitoring Surat Mahasiswa')

@push('style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endpush

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Monitoring Surat Mahasiswa</h1>
        </div>

        <div class="card">
            <div class="card-body">

                {{-- Filter Prodi --}}
                <form method="GET" class="form-inline mb-4">
                    <label for="prodi_id" class="mr-2">Filter Prodi:</label>
                    <select name="prodi_id" id="prodi_id" class="form-control mr-2" onchange="this.form.submit()">
                        <option value="">Semua</option>
                        @foreach($prodis as $prodi)
                            <option value="{{ $prodi->id }}" {{ request('prodi_id') == $prodi->id ? 'selected' : '' }}>
                                {{ $prodi->nama_prodi }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <div class="table-responsive">
                    <table id="table-surat" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Anggota Kelompok</th>
                                <th>Perihal</th>
                                <th>Judul</th>
                                <th>Dosen Pembimbing 1</th>
                                <th>Dosen Pembimbing 2</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($suratList as $index => $surat)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <ul class="mb-0">
                                        @foreach($surat->mahasiswa->kelompok->anggota ?? [] as $mhs)
                                            <li>{{ $mhs->nama_mhs }} ({{ $mhs->nim_mhs }})</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>{{ $surat->perihal }}</td>
                                <td>{{ $surat->judul_ta ?? '-' }}</td>
                                <td>{{ $surat->mahasiswa->pengajuanDiterima->dosen1->dosen->nama_dosen ?? '-' }}</td>
                                <td>{{ $surat->mahasiswa->pengajuanDiterima->dosen2->dosen->nama_dosen ?? '-' }}</td>
                                <td>{{ $surat->status }}</td>
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
        $('#table-surat').DataTable({
            language: {
                search: "Cari Mahasiswa / Perihal / Status:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                zeroRecords: "Data tidak ditemukan",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data tersedia",
                infoFiltered: "(disaring dari total _MAX_ data)"
            },
            pageLength: 10,
            responsive: true
        });
    });
</script>
@endpush
