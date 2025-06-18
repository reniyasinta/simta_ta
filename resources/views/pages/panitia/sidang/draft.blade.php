@extends('layouts.app')

@section('title', 'Berkas Sidang - Draft')

@push('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Berkas Sidang - Draft</h1>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4>Daftar Draft Sidang Mahasiswa</h4>
            </div>
            <div class="table-responsive">
                <table id="table-sidang-draft" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Mahasiswa</th>
                            <th>NIM</th>
                            <th>Prodi</th>
                            <th>Laporan TA</th>
                            <th>Lembar Konsultasi</th>
                            <th>Status Draft</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sidangList as $index => $sidang)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $sidang->mahasiswa->nama_mhs ?? '-' }}</td>
                                <td>{{ $sidang->mahasiswa->nim_mhs ?? '-' }}</td>
                                <td>{{ $sidang->mahasiswa->prodi->nama_prodi ?? '-' }}</td>
                                <td>
                                    @if ($sidang->laporan_TA)
                                        <a href="{{ asset($sidang->laporan_TA) }}" target="_blank" class="btn btn-sm btn-info">Lihat</a>
                                    @else
                                        <span class="text-muted">Belum Upload</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($sidang->lembar_konsultasi)
                                        <a href="{{ asset($sidang->lembar_konsultasi) }}" target="_blank" class="btn btn-sm btn-info">Lihat</a>
                                    @else
                                        <span class="text-muted">Belum Upload</span>
                                    @endif
                                </td>
                                <td>{{ $sidang->status_draft_dosen1 ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-danger">Belum ada data.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#table-sidang-draft').DataTable({
                "language": {
                    "search": "Cari Mahasiswa / NIM / Prodi:",
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Data tidak ditemukan",
                    "info": "Menampilkan _PAGE_ dari _PAGES_",
                    "infoEmpty": "Tidak ada data",
                    "infoFiltered": "(filtered from _MAX_ total records)"
                },
                "pageLength": 10
            });
        });
    </script>
@endpush
