@extends('layouts.app')

@section('title', 'ACC Revisi Sidang')

@push('style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endpush

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Persetujuan Revisi</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table-revisi" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Mahasiswa</th>
                                <th>Revisi Laporan</th>
                                <th>Status Revisi</th>
                                <th>Catatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sidangList as $index => $sidang)
                            <tr>
                                <td>{{ $index + 1 }}</td>

                                {{-- Nama Mahasiswa --}}
                                <td>
                                    @php
                                        $anggotaList = [];
                                        if ($sidang->kelompok) {
                                            foreach([$sidang->kelompok->anggota1, $sidang->kelompok->anggota2, $sidang->kelompok->anggota3] as $anggota) {
                                                if ($anggota && $anggota->mahasiswa) {
                                                    $anggotaList[] = $anggota->mahasiswa->nama_mhs . ' (' . $anggota->mahasiswa->nim_mhs . ')';
                                                }
                                            }
                                        } else {
                                            $anggotaList[] = '<span class="text-danger">Kelompok tidak ditemukan</span>';
                                        }
                                    @endphp
                                    {!! implode('<br>', $anggotaList) !!}
                                </td>

                                {{-- Revisi Laporan --}}
                                <td>
                                    @if($sidang->revisi_laporan)
                                        <a href="{{ asset($sidang->revisi_laporan) }}" target="_blank" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                    @else
                                        <span class="text-muted">Belum Upload</span>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td>
                                    @php
                                        $penguji_ke = null;
                                        if ($sidang->penguji_1_id == auth()->user()->id) $penguji_ke = 1;
                                        elseif ($sidang->penguji_2_id == auth()->user()->id) $penguji_ke = 2;
                                        elseif ($sidang->penguji_3_id == auth()->user()->id) $penguji_ke = 3;

                                        $status = $sidang->{'status_revisi_penguji_' . $penguji_ke} ?? 'Menunggu';
                                        $catatan = $sidang->{'catatan_penguji_' . $penguji_ke} ?? '-';
                                    @endphp
                                    {{ $status }}
                                </td>

                                {{-- Catatan --}}
                                <td>
                                    @if($penguji_ke && $status === 'Menunggu')
                                        <form action="{{ route('dosen.sidang.updateStatusRevisi', [$sidang->id_sidang, $penguji_ke]) }}" method="POST" id="form-{{ $sidang->id_sidang }}-{{ $penguji_ke }}">
                                            @csrf
                                            <textarea name="catatan_revisi" class="form-control form-control-sm" rows="2" placeholder="Catatan Revisi">{{ $catatan }}</textarea>
                                            <input type="hidden" name="status_revisi" id="status_revisi_{{ $sidang->id_sidang }}_{{ $penguji_ke }}">
                                        </form>
                                    @else
                                        {{ $catatan }}
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td>
                                    @if($penguji_ke && $status === 'Menunggu')
                                        <div class="d-flex gap-2 flex-wrap">
                                            <button
                                                type="submit"
                                                form="form-{{ $sidang->id_sidang }}-{{ $penguji_ke }}"
                                                onclick="document.getElementById('status_revisi_{{ $sidang->id_sidang }}_{{ $penguji_ke }}').value = 'Disetujui'"
                                                class="btn btn-sm btn-success">
                                                ACC Revisi
                                            </button>
                                            <button
                                                type="submit"
                                                form="form-{{ $sidang->id_sidang }}-{{ $penguji_ke }}"
                                                onclick="document.getElementById('status_revisi_{{ $sidang->id_sidang }}_{{ $penguji_ke }}').value = 'Revisi'"
                                                class="btn btn-sm btn-danger">
                                                Tolak Revisi
                                            </button>
                                        </div>
                                    @elseif($penguji_ke)
                                        <span class="badge badge-success">Sudah divalidasi</span>
                                    @else
                                        <span class="text-muted">Anda bukan penguji</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data.</td>
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
        $('#table-revisi').DataTable({
            "language": {
                "search": "Cari Mahasiswa / NIM:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Data tidak ditemukan",
                "info": "Menampilkan _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data",
                "infoFiltered": "(disaring dari total _MAX_ data)"
            },
            "pageLength": 10
        });
    });
</script>
@endpush
