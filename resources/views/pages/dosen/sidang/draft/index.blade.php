@extends('layouts.app')

@section('title', 'ACC Draft Sidang')

@push('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endpush

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Persetujuan Maju Sidang</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Mahasiswa</th>
                                <th>Laporan Draft</th>
                                <th>Status Draft</th>
                                <th>Catatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $sortedSidangList = $sidangList->sortByDesc('created_at')->values();
                            @endphp

                            @forelse($sortedSidangList as $index => $sidang)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @php
                                        $anggotaList = [];
                                        foreach([$sidang->kelompok->anggota1, $sidang->kelompok->anggota2, $sidang->kelompok->anggota3] as $anggota) {
                                            if ($anggota && $anggota->mahasiswa) {
                                                $anggotaList[] = $anggota->mahasiswa->nama_mhs . ' (' . $anggota->mahasiswa->nim_mhs . ')';
                                            }
                                        }
                                    @endphp
                                    {!! implode('<br>', $anggotaList) !!}
                                </td>

                                <td>
                                    @if($sidang->laporan_TA)
                                    <a href="{{ asset($sidang->laporan_TA) }}" target="_blank" class="btn btn-sm btn-info">Lihat</a>
                                @else
                                    <span class="text-muted">Belum Upload</span>
                                @endif
                                </td>

                                <td>
                                    @php
                                        $status = '-';
                                        if ($sidang->id_dosen1 == auth()->user()->id) {
                                            $status = $sidang->status_draft_dosen1 ?? '-';
                                        } elseif ($sidang->id_dosen2 == auth()->user()->id) {
                                            $status = $sidang->status_draft_dosen2 ?? '-';
                                        }
                                    @endphp
                                    {{ $status }}
                                </td>
<td>
    @php
        $catatan = '-';
        if ($sidang->id_dosen1 == auth()->user()->id) {
            $catatan = $sidang->catatan_draft_dosen1 ?? '';
        } elseif ($sidang->id_dosen2 == auth()->user()->id) {
            $catatan = $sidang->catatan_draft_dosen2 ?? '';
        }
    @endphp

    @if ($status == '-' || $status == 'Menunggu')
        <form action="{{ route('dosen.sidang.updateStatusDraft', $sidang->id_sidang) }}" method="POST">
            @csrf
            <textarea name="catatan" class="form-control form-control-sm mb-2" placeholder="Catatan (opsional)">{{ old('catatan', $catatan) }}</textarea>
    @else
        {{ $catatan }}
    @endif
</td>

</td>
<td>
    @if ($status == '-' || $status == 'Menunggu')
            <div class="d-flex gap-2">
                <button type="submit" name="status_draft" value="Disetujui" class="btn btn-sm btn-success">Setuju</button>
                <button type="submit" name="status_draft" value="Revisi" class="btn btn-sm btn-warning">Revisi</button>
            </div>
        </form>
    @else
        <span class="text-muted">Sudah Divalidasi</span>
    @endif
</td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data</td>
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
        $('.table').DataTable({
            language: {
                search: "Cari Mahasiswa / NIM:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                zeroRecords: "Data tidak ditemukan",
                info: "Menampilkan _PAGE_ dari _PAGES_",
                infoEmpty: "Tidak ada data",
                infoFiltered: "(disaring dari _MAX_ total data)"
            },
            pageLength: 10
        });
    });
</script>
@endpush
