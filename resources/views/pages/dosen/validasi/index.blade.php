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

                                {{-- KETERANGAN --}}
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

                                {{-- AKSI --}}
                                <td>
                                    @if($item->status === 'Menunggu')
                                        <div class="d-flex gap-2">
                                            <button form="form-{{ $item->id_ajuan }}" type="submit" name="status" value="Diterima" class="btn btn-sm btn-success">
                                                Setujui
                                            </button>
                                            <button form="form-{{ $item->id_ajuan }}" type="submit" name="status" value="Ditolak" class="btn btn-sm btn-danger">
                                                Tolak
                                            </button>
                                        </div>
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
