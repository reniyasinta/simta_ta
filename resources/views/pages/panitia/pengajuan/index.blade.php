@extends('layouts.app')

@section('title', 'Pengajuan Mahasiswa')

@push('style')
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

        @php
            $userProdiId = auth()->user()->id_prodi;
            $groupMapping = [
                [1, 2], // TI & SIKC
                [3, 4], // Listrik & TRPE
                [5, 6], // Elka & TRO
            ];
            $userGroup = collect($groupMapping)->first(fn($group) => in_array($userProdiId, $group)) ?? [];
            $availableProdis = \App\Models\Prodi::whereIn('id', $userGroup)->pluck('nama_prodi', 'id');
        @endphp

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Tabel Pengajuan Mahasiswa</h4>
                <a href="{{ route('panitia.pengajuan.export') }}" class="btn btn-success btn-sm">Export Excel</a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="table-pengajuan" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kelompok</th>
                                <th>Kelas</th>
                                <th>Prodi</th>
                                <th>Judul</th>
                                <th>Dosen Pembimbing 1</th>
                                <th>Dosen Pembimbing 2</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                    @php
                        $filteredPengajuan = $pengajuanList
                            ->sortByDesc('created_at') // Urutkan dari yang terbaru
                            ->filter(function($pengajuan) {
                                $selectedProdi = request('prodi');
                                $anggota1 = $pengajuan->kelompok->anggota1->mahasiswa ?? null;
                                return !$selectedProdi || ($anggota1 && $anggota1->id_prodi == $selectedProdi);
                            })->values();
                    @endphp
                            @forelse($filteredPengajuan as $key => $p)
                                @php
                                    $prodi = $p->kelompok->anggota1->mahasiswa->prodi->nama_prodi ?? '-';
                                @endphp
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <ul>
                                            @forelse($p->kelompok->anggota ?? [] as $mhs)
                                                <li>{{ $mhs->nama_mhs }} ({{ $mhs->nim_mhs }})</li>
                                            @empty
                                                <li><em>Tidak ada anggota</em></li>
                                            @endforelse
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            @foreach($p->kelompok->anggota ?? [] as $mhs)
                                                <li>{{ $mhs->kelas ?? '-' }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>{{ $prodi }}</td>
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
                                    <td colspan="7" class="text-center">Belum ada pengajuan.</td>
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
        $('#table-pengajuan').DataTable({
            language: {
                search: "Cari Mahasiswa / Kelas / NIM / Judul:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                zeroRecords: "Data tidak ditemukan",
                info: "Menampilkan _PAGE_ dari _PAGES_",
                infoEmpty: "Tidak ada data",
                infoFiltered: "(difilter dari _MAX_ total data)"
            },
            pageLength: 10
        });
    });
</script>
@endpush
