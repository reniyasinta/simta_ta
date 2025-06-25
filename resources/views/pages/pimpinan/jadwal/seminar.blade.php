@extends('layouts.app')

@section('title', 'Jadwal Seminar')

@push('style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endpush

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Rekapitulasi Jadwal Seminar</h1>
        </div>
        <div class="card">
            <div class="card-body">
            <form method="GET" action="{{ route('pimpinan.jadwal.seminar') }}" class="form-inline mb-3">
                <label for="prodi_filter" class="mr-2">Filter Prodi:</label>
                <select name="prodi_id" id="prodi_filter" class="form-control mr-2" onchange="this.form.submit()">
                    <option value="">Semua</option>
                    @foreach($prodis as $prodi)
                        <option value="{{ $prodi->id }}" {{ request('prodi_id') == $prodi->id ? 'selected' : '' }}>
                            {{ $prodi->nama_prodi }}
                        </option>
                    @endforeach
                </select>
            </form>
             {{-- Tabel Jadwal Seminar --}}
                <div class="table-responsive">
                    <table id="table-seminar" class="table table-striped table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Jam</th>
                                <th>Ruangan</th>
                                <th>Kelompok</th>
                                <th>Prodi</th>
                                <th>Judul</th>
                                <th>Penguji 1</th>
                                <th>Penguji 2</th>
                                <th>Penguji 3</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jadwals as $index => $jadwal)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ $jadwal->jam_selesai ? \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') : '-' }}</td>
                                <td>{{ $jadwal->ruangan ?? '-' }}</td>
                                <td>
                                    <ul class="mb-0">
                                        @foreach($jadwal->pengajuan?->kelompok?->anggota ?? [] as $mhs)
                                            <li>{{ $mhs->nama_mhs }} ({{ $mhs->nim_mhs }})</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>{{ $jadwal->pengajuan?->kelompok?->anggota->first()->prodi->nama_prodi ?? '-' }}</td>
                                <td>{{ $jadwal->pengajuan?->judul_ta ?? '-' }}</td>
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
        $('#table-seminar').DataTable({
            language: {
                search: "Cari Mahasiswa / NIM / Judul / Penguji:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                zeroRecords: "Belum ada data seminar yang tersedia",
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
