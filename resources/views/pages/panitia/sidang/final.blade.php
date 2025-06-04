@extends('layouts.app')

@section('title', 'Berkas Sidang - Final')

@push('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Berkas Sidang - Final</h1>
            </div>

            <div class="table-responsive">
                <table id="table-sidang-final" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Mahasiswa</th>
                            <th>NIM</th>
                            <th>Prodi</th>
                            <th>Laporan Akhir</th>
                            <th>Lembar Konsultasi</th>
                            <th>Hasil Sidang</th>
                            <th>Status Final</th>
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
                                    @if ($sidang->laporan_akhir)
                                        <a href="{{ asset($sidang->laporan_akhir) }}" target="_blank" class="btn btn-sm btn-info">Lihat</a>
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
                                <td>
                                    @if ($sidang->hasil_sidang)
                                        <a href="{{ asset($sidang->hasil_sidang) }}" target="_blank" class="btn btn-sm btn-info">Lihat</a>
                                    @else
                                        <span class="text-muted">Belum Upload</span>
                                    @endif
                                </td>
                                <td>{{ $sidang->status_final ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-danger">Belum ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#table-sidang-final').DataTable({
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
