@extends('layouts.app')

@section('title', 'Persetujuan Laporan TA')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Persetujuan Laporan TA</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="section-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Mahasiswa</th>
                            <th>Revisi Laporan</th>
                            <th>Status</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sidangs as $key => $sidang)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $sidang->mahasiswa->nama_mhs ?? '-' }}</td>

                                {{-- Kolom Revisi --}}
                                <td>
                                    @if ($sidang->revisi_laporan)
                                        <a href="{{ asset($sidang->revisi_laporan) }}" target="_blank" class="btn btn-sm btn-link">Download</a>
                                    @else
                                        <span class="text-muted">Belum diunggah</span>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td>
                                    @if($sidang->status === 'Diterima')
                                        <span class="badge bg-success">Diterima</span>
                                    @elseif($sidang->status === 'Revisi')
                                        <span class="badge bg-danger">Revisi</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    @endif
                                </td>

                                {{-- Catatan --}}
                                <td>
                                    @if($sidang->status === 'Menunggu' && $sidang->revisi_laporan)
                                        <form action="{{ route('dosen.sidang.verifikasi', $sidang->id_sidang) }}" method="POST" id="form-{{ $sidang->id_sidang }}">
                                            @csrf
                                            <textarea name="catatan_dosen" class="form-control form-control-sm" rows="2" placeholder="Isi catatan (opsional)"></textarea>
                                        </form>
                                    @else
                                        {{ $sidang->catatan_dosen ?? '-' }}
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td>
                                    @if($sidang->status === 'Menunggu' && $sidang->revisi_laporan)
                                        <div class="d-flex gap-2">
                                            <button form="form-{{ $sidang->id_sidang }}" type="submit" name="status" value="Diterima" class="btn btn-sm btn-success">
                                                Setujui
                                            </button>
                                            <button form="form-{{ $sidang->id_sidang }}" type="submit" name="status" value="Revisi" class="btn btn-sm btn-danger">
                                                Revisi
                                            </button>
                                        </div>
                                    @elseif($sidang->status === 'Menunggu')
                                        <span class="text-muted">Menunggu unggahan mahasiswa</span>
                                    @else
                                        <span class="text-muted">Sudah diverifikasi</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data sidang.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
@endsection
