@extends('layouts.app')

@section('title', 'Berkas Final Mahasiswa')

@push('style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endpush

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Berkas Final Mahasiswa</h1>
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
                    <table id="table-final" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Mahasiswa</th>
                                <th>NIM</th>
                                <th>Prodi</th>
                                <th>Status Validasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sidangList as $i => $sidang)
                                @php
                                    $anggota = $sidang->kelompok->anggota ?? collect();
                                @endphp
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>
                                        @foreach ($anggota as $m)
                                            <div>{{ $m->nama_mhs }}</div>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($anggota as $m)
                                            <div>{{ $m->nim_mhs }}</div>
                                        @endforeach
                                    </td>
                                    <td>{{ $anggota->first()->prodi->nama_prodi ?? '-' }}</td>
                                    <td>
                                        @if ($sidang->status_final === 'Disetujui')
                                            <span class="badge badge-success">Disetujui Panitia</span>
                                        @elseif ($sidang->status_final === 'Revisi')
                                            <span class="badge badge-danger">Revisi</span>
                                        @else
                                            <span class="badge badge-secondary">Belum Divalidasi</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-danger">Belum ada data.</td>
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
    $(document).ready(function () {
        $('#table-final').DataTable({
            language: {
                search: "Cari Mahasiswa / NIM / Prodi:",
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
