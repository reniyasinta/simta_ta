@extends('layouts.app')

@section('title', 'Rekapitulasi Kuota Bimbingan')

@push('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endpush

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Rekapitulasi Kuota Bimbingan</h1>
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
                    <table id="table-kuota" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Dosen</th>
                                <th>Prodi</th>
                                <th>Kuota P1</th>
                                <th>Kuota P2</th>
                                <th>Terpakai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dosenList as $index => $dosen)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $dosen->nama_dosen }}</td>
                                    <td>{{ $dosen->prodi->nama_prodi ?? '-' }}</td>
                                    <td>{{ $dosen->kuota_bimbingan }}</td>
                                    <td>{{ $dosen->kuota_p2 }}</td>
                                    <td>{{ $dosen->bimbingan_terpakai }}</td>
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
            $('#table-kuota').DataTable({
                language: {
                    search: "Cari Dosen / Prodi:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan _PAGE_ dari _PAGES_",
                    infoEmpty: "Tidak ada data",
                    infoFiltered: "(disaring dari total _MAX_ data)"
                },
                pageLength: 10
            });
        });
    </script>
@endpush
