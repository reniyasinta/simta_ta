@extends('layouts.app')

@section('title', 'Berkas Sempro')

@push('style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endpush

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Berkas Sempro</h1>
        </div>

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
                <div class="card-header">
                    <h4>Daftar Berkas Sempro</h4>
                </div>
                <div class="card-body">
                            <div class="section-body">
                    <table id="table-sempro" class="table table-bordered table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th class="text-center" style="width: 50px;">No</th>
                                <th class="text-center">Anggota Kelompok</th>
                                <th class="text-center">Kelas</th>
                                <th class="text-center">Prodi</th>
                                <th class="text-center">Proposal TA</th>
                                <th class="text-center">Form Persetujuan</th>
                                <th class="text-center">Berita Acara</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $filteredList = $pengajuanList->filter(function($pengajuan) {
                                    $selectedProdi = request('prodi');
                                    $anggota1 = $pengajuan->kelompok->anggota1->mahasiswa ?? null;
                                    return !$selectedProdi || ($anggota1 && $anggota1->id_prodi == $selectedProdi);
                                })->values();
                            @endphp

                            @forelse ($filteredList as $index => $pengajuan)
                                @php
                                    $sempro = $semproList->where('id_ajuan', $pengajuan->id_ajuan)->first();
                                    $prodi = $pengajuan->kelompok->anggota1->mahasiswa->prodi->nama_prodi ?? '-';
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <strong>Anggota:</strong>
                                        <ul style="padding-left: 16px;">
                                            @if($pengajuan->kelompok->anggota1?->mahasiswa)
                                                <li>{{ $pengajuan->kelompok->anggota1->mahasiswa->nama_mhs }} ({{ $pengajuan->kelompok->anggota1->mahasiswa->nim_mhs }})</li>
                                            @endif
                                            @if($pengajuan->kelompok->anggota2?->mahasiswa)
                                                <li>{{ $pengajuan->kelompok->anggota2->mahasiswa->nama_mhs }} ({{ $pengajuan->kelompok->anggota2->mahasiswa->nim_mhs }})</li>
                                            @endif
                                            @if($pengajuan->kelompok->anggota3?->mahasiswa)
                                                <li>{{ $pengajuan->kelompok->anggota3->mahasiswa->nama_mhs }} ({{ $pengajuan->kelompok->anggota3->mahasiswa->nim_mhs }})</li>
                                            @endif
                                        </ul>
                                    </td>
                                    <td class="text-center">
                                            @if($pengajuan->kelompok->anggota1?->mahasiswa)
                                                <li>{{ $pengajuan->kelompok->anggota1->mahasiswa->kelas ?? '-' }}</li>
                                            @endif
                                            @if($pengajuan->kelompok->anggota2?->mahasiswa)
                                                <li>{{ $pengajuan->kelompok->anggota2->mahasiswa->kelas ?? '-' }}</li>
                                            @endif
                                            @if($pengajuan->kelompok->anggota3?->mahasiswa)
                                                <li>{{ $pengajuan->kelompok->anggota3->mahasiswa->kelas ?? '-' }}</li>
                                            @endif
                                        </ul>
                                    </td>
                                    <td class="text-center">{{ $prodi }}</td>
                                    <td class="text-center">
                                        @if ($sempro && $sempro->proposal_ta)
                                            <a href="{{ asset($sempro->proposal_ta) }}" target="_blank" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                        @else
                                            <span class="badge badge-secondary">Belum Upload</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($sempro && $sempro->form_persetujuan_sempro)
                                            <a href="{{ asset($sempro->form_persetujuan_sempro) }}" target="_blank" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                        @else
                                            <span class="badge badge-secondary">Belum Upload</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($sempro && $sempro->berita_acara_sempro)
                                            <a href="{{ asset($sempro->berita_acara_sempro) }}" target="_blank" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                        @else
                                            <span class="badge badge-secondary">Belum Upload</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-danger">Belum ada pengajuan Diterima.</td>
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
        $('#table-sempro').DataTable({
            "language": {
                "search": "Cari Mahasiswa / NIM :",
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
