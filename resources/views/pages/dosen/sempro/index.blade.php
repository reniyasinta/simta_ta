@extends('layouts.app')

@section('title', 'Validasi SEMPRO')

@push('style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endpush

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Validasi Sempro</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('home') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Validasi Sempro</div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @php $user = Auth::user(); @endphp

        <div class="card shadow-sm">
            <div class="card-body">
         <!-- Filter Prodi -->
        <form method="GET" class="form-inline mb-3">
            <label for="prodi" class="mr-2">Filter Prodi:</label>
            <select name="prodi" id="prodi" class="form-control mr-2" onchange="this.form.submit()">
                <option value="">-- Semua Prodi --</option>
                @foreach($availableProdis as $id => $nama)
                    <option value="{{ $id }}" {{ request('prodi') == $id ? 'selected' : '' }}>{{ $nama }}</option>
                @endforeach
            </select>
        </form>
                <div class="table-responsive">
                    <table id="table-sempro" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Anggota Kelompok</th>
                                <th>Kelas</th>
                                <th>Prodi</th>
                                <th>Judul TA</th>
                                <th>Proposal TA</th>
                                <th>Status</th>
                                <th>Catatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                                @php
                                    $sortedSempros = $sempros->sortByDesc('created_at')->values();
                                @endphp

                                @forelse($sortedSempros as $key => $item)
                                @php
                                    $prodi = $item->pengajuan->kelompok->anggota1->mahasiswa->prodi->nama_prodi ?? '-';
                                @endphp
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <ul class="mb-0">
                                            @foreach($item->pengajuan->kelompok->anggota as $mhs)
                                                <li>{{ $mhs->nama_mhs }} ({{ $mhs->nim_mhs }})</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                        <ul class="mb-0">
                                            @foreach($item->pengajuan->kelompok->anggota as $mhs)
                                                <li>{{ $mhs->kelas ?? '-' }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>{{ $prodi }}</td>
                                    <td>{{ $item->pengajuan->judul_ta ?? '-' }}</td>
                                    <td>
                                        @if($item->proposal_ta)
                                            <a href="{{ asset($item->proposal_ta) }}" target="_blank" class="btn btn-sm btn-link">Lihat</a>
                                        @else
                                            <span class="text-muted">Belum ada</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            if ($item->pengajuan->id_dosen1 == $user->id) {
                                                $status = $item->status_proposal_ta_dospem1;
                                            } elseif ($item->pengajuan->id_dosen2 == $user->id) {
                                                $status = $item->status_proposal_ta_dospem2;
                                            } else {
                                                $status = 'Menunggu';
                                            }
                                        @endphp
                                        @if($status === 'Disetujui')
                                            <span class="badge bg-success text-white">Disetujui</span>
                                        @elseif($status === 'Revisi')
                                            <span class="badge bg-warning text-dark">Revisi</span>
                                        @else
                                            <span class="badge bg-secondary text-white">Menunggu</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            if ($item->pengajuan->id_dosen1 == $user->id) {
                                                $catatan = $item->catatan_dospem1;
                                            } elseif ($item->pengajuan->id_dosen2 == $user->id) {
                                                $catatan = $item->catatan_dospem2;
                                            } else {
                                                $catatan = '-';
                                            }
                                        @endphp

                                        @if($status === 'Menunggu')
                                            <form action="{{ route('dosen.sempro.submit', $item->id_sempro) }}" method="POST" id="form-{{ $item->id_sempro }}">
                                                @csrf
                                                <textarea name="catatan" class="form-control form-control-sm" rows="2" placeholder="Isi catatan (opsional)"></textarea>
                                            </form>
                                        @else
                                            {{ $catatan ?? '-' }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($status === 'Menunggu')
                                            <div class="d-flex flex-wrap">
                                                <button form="form-{{ $item->id_sempro }}" type="submit" name="status" value="Disetujui" class="btn btn-sm btn-success mr-2">
                                                    Setujui
                                                </button>
                                                <button form="form-{{ $item->id_sempro }}" type="submit" name="status" value="Revisi" class="btn btn-sm btn-warning">
                                                    Revisi
                                                </button>
                                            </div>
                                        @else
                                            <span class="text-muted">Sudah divalidasi</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada pengajuan.</td>
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
        $('#table-sempro').DataTable({
            language: {
                search: "Cari Mahasiswa / Kelas / NIM / Judul:",
                lengthMenu: "Tampilkan _MENU_ data",
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
