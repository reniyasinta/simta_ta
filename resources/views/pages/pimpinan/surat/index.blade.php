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

                {{-- Filter Dropdown Prodi --}}
                <form method="GET" action="{{ route('pimpinan.surat.index') }}" class="mb-4 w-50">
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
                    <table id="table-surat" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Mahasiswa</th>
                                <th>Perihal</th>
                                <th>Judul</th>
                                <th>Dosen Pembimbing</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($suratList as $index => $surat)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    {{ $surat->mahasiswa->nama_mhs ?? '-' }}
                                    ({{ $surat->mahasiswa->nim_mhs ?? '-' }})
                                </td>
                                <td>{{ $surat->perihal }}</td>
                                <td>{{ $surat->judul_ta ?? '-' }}</td>
                                <td>{{ $surat->dosen_pembimbing ?? '-' }}</td>
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
