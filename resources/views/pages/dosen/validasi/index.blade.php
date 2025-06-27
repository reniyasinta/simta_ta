@extends('layouts.app')

@section('title', 'Validasi Pengajuan Pembimbing')

@push('style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endpush

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Validasi Pengajuan Pembimbing</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('home') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Validasi Pengajuan</div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

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
                    <table id="table-validasi" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Prodi</th>
                                <th>Anggota Kelompok</th>
                                <th>Judul TA</th>
                                <th>Proposal</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengajuan as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->kelompok->anggota1->mahasiswa->prodi->nama_prodi ?? '-' }}</td>
                                    <td>
                                        <ul class="mb-0">
                                            @foreach($item->kelompok->anggota as $mhs)
                                                <li>{{ $mhs->nama_mhs }} ({{ $mhs->nim_mhs }})</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>{{ $item->judul_ta ?? '-' }}</td>
                                    <td>
                                        @if($item->proposal)
                                            <a href="{{ asset('storage/proposal/' . $item->proposal) }}" target="_blank" class="btn btn-sm btn-link">Lihat</a>
                                        @else
                                            <span class="text-muted">Belum ada</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->status === 'Diterima')
                                            <span class="badge bg-success text-white">Acc</span>
                                        @elseif($item->status === 'Ditolak')
                                            <span class="badge bg-danger text-white">Ditolak</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Menunggu</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->status === 'Menunggu')
                                            <form action="{{ route('dosen.validasi.submit', $item->id_ajuan) }}" method="POST" id="form-{{ $item->id_ajuan }}">
                                                @csrf
                                                <textarea name="keterangan" class="form-control form-control-sm" rows="2" placeholder="Isi keterangan (opsional)"></textarea>
                                            </form>
                                        @else
                                            {{ $item->keterangan ?? '-' }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->status === 'Menunggu')
                                            <div class="d-flex gap-2">
                                                <button form="form-{{ $item->id_ajuan }}" type="submit" name="status" value="Diterima" class="btn btn-sm btn-success">Acc</button>
                                                <button form="form-{{ $item->id_ajuan }}" type="submit" name="status" value="Ditolak" class="btn btn-sm btn-danger">Tolak</button>
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
        $('#table-validasi').DataTable({
            "language": {
                "search": "Cari Mahasiswa / NIM / Judul:",
                "lengthMenu": "Tampilkan _MENU_ data",
                "zeroRecords": "Data tidak ditemukan",
                "info": "Menampilkan _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data",
                "infoFiltered": "(disaring dari _MAX_ total data)"
            },
            "pageLength": 10
        });
    });
</script>
@endpush
