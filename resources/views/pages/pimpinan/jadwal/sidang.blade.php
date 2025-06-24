@extends('layouts.app')

@section('title', 'Jadwal Sidang')

@push('style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endpush

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Rekapitulasi Jadwal Sidang</h1>
        </div>

        <div class="card">
            <div class="card-body">
<form method="GET" action="{{ route('pimpinan.jadwal.sidang') }}" class="mb-4 w-50">
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
                <div class="table-responsive">
                    <table id="table-sidang" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Kelompok</th>
                                <th>Program Studi</th>
                                <th>Judul</th>
                                <th>Ketua Penguji</th>
                                <th>Penguji 1</th>
                                <th>Penguji 2</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jadwals as $index => $jadwal)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $jadwal->tanggal }}</td>
                                <td>{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
                                <td>{{ $jadwal->nama }}</td>
                                <td>{{ $jadwal->kelompok->anggota->first()->prodi->nama_prodi ?? '-' }}</td>
                                <td>{{ $jadwal->judul_ta }}</td>
                                <td>{{ $jadwal->penguji1->name ?? '-' }}</td>
                                <td>{{ $jadwal->penguji2->name ?? '-' }}</td>
                                <td>{{ $jadwal->penguji3->name ?? '-' }}</td>
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
        $('#table-sidang').DataTable({
            language: {
                search: "Cari data sidang:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                zeroRecords: "Belum ada data sidang yang tersedia",
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
