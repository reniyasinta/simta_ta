@extends('layouts.app')

@section('title', 'Manajemen Kuota Dosen')

@push('style')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endpush

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Manajemen Kuota Dosen</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('home') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Penentuan Kuota Dosen</div>
            </div>
        </div>

        <div class="section-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- Mulai Card --}}
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Tabel Manajemen Kuota Dosen</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table-kuota-dosen" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Dosen</th>
                                    <th>Prodi</th>
                                    <th>Kuota (P1)</th>
                                    <th>Terisi</th>
                                    <th>Sisa</th>
                                    <th>Kuota (P2)</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dosenList as $key => $dosen)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $dosen->nama_dosen }}</td>
                                    <td>{{ $dosen->prodi->nama_prodi ?? '-' }}</td>
                                    <td>
                                        <form action="{{ route('panitia.kuota.update', $dosen->id_dosen) }}" method="POST" class="form-inline">
                                            @csrf
                                            <input type="number" name="kuota_bimbingan" value="{{ $dosen->kuota_bimbingan }}" class="form-control form-control-sm" style="width: 80px;" min="0">
                                    </td>
                                    <td>{{ $dosen->bimbingan_terpakai }}</td>
                                    <td>
                                        @php
                                            $sisa = ($dosen->kuota_bimbingan ?? 0) - $dosen->bimbingan_terpakai;
                                            $warna = 'bg-success text-white';
                                            if ($sisa <= 2 && $sisa > 0) {
                                                $warna = 'bg-warning text-dark';
                                            } elseif ($sisa <= 0) {
                                                $warna = 'bg-danger';
                                            }
                                        @endphp
                                        <span class="badge {{ $warna }}">{{ $sisa }}</span>
                                    </td>
                                    <td>
                                        <input type="number" name="kuota_p2" value="{{ $dosen->kuota_p2 ?? 0 }}" class="form-control form-control-sm" style="width: 80px;" min="0">
                                    </td>
                                    <td>
                                            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach

                                @if($dosenList->isEmpty())
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data dosen.</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- Akhir Card --}}
        </div>
    </section>
</div>
@endsection

@push('scripts')
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#table-kuota-dosen').DataTable({
                "language": {
                    "search": "Cari Nama Dosen / Prodi:",
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
