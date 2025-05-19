@extends('layouts.app')

@section('title', 'Validasi Pengajuan Pembimbing')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Validasi Pengajuan Pembimbing</h1>
        </div>

        <div class="section-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
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
                                <td>
                                    <ul>
                                        @foreach($item->kelompok->anggota as $anggota)
                                            <li>{{ $anggota->nama_mhs }} ({{ $anggota->nim_mhs }})</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>{{ $item->judul_ta }}</td>
                                <td>
                                    @if($item->proposal)
                                        <a href="{{ asset('storage/proposal/' . $item->proposal) }}" target="_blank" class="btn btn-sm btn-link">Lihat</a>
                                    @else
                                        <span class="text-muted">Belum ada</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->status === 'Diterima')
                                        <span class="badge bg-success">Diterima</span>
                                    @elseif($item->status === 'Ditolak')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    @endif
                                </td>
                                <td>{{ $item->keterangan ?? '-' }}</td>
                                <td>
                                    @if($item->status === 'Menunggu')
                                    <form action="{{ route('dosen.validasi.submit', $item->id_ajuan) }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <select name="status" class="form-control form-control-sm mb-2" required>
                                                <option value="">-- Pilih Status --</option>
                                                <option value="Diterima">Diterima</option>
                                                <option value="Ditolak">Ditolak</option>
                                            </select>
                                            <textarea name="keterangan" class="form-control form-control-sm mb-2" rows="2" placeholder="Keterangan (opsional)"></textarea>
                                            <button type="submit" class="btn btn-sm btn-primary">Kirim</button>
                                        </div>
                                    </form>
                                    @else
                                        <span class="text-muted">Sudah divalidasi</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada pengajuan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
@endsection
