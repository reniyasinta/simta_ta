@extends('layouts.app')

@section('title', 'Validasi SEMPRO')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Validasi SEMPRO</h1>
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
                            <th>Anggota Kelompok</th>
                            <th>Judul TA</th>
                            <th>Form Persetujuan</th>
                            <th>Status</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sempros as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>

                                {{-- Anggota kelompok --}}
                                <td>
                                    <ul>
                                        @if($item->pengajuan->kelompok->anggota1)
                                            <li>{{ $item->pengajuan->kelompok->anggota1->mahasiswa->nama_mhs ?? '-' }}</li>
                                        @endif
                                        @if($item->pengajuan->kelompok->anggota2)
                                            <li>{{ $item->pengajuan->kelompok->anggota2->mahasiswa->nama_mhs ?? '-' }}</li>
                                        @endif
                                        @if($item->pengajuan->kelompok->anggota3)
                                            <li>{{ $item->pengajuan->kelompok->anggota3->mahasiswa->nama_mhs ?? '-' }}</li>
                                        @endif
                                    </ul>
                                </td>

                                {{-- Judul TA --}}
                                <td>{{ $item->pengajuan->judul_ta ?? '-' }}</td>

                                {{-- Form Persetujuan --}}
                                <td>
                                    @if($item->form_persetujuan_sempro)
                                        <a href="{{ asset($item->form_persetujuan_sempro) }}" target="_blank" class="btn btn-sm btn-link">Lihat</a>
                                    @else
                                        <span class="text-muted">Belum ada</span>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td>
                                    @php
                                        $user = Auth::user();
                                        if ($item->pengajuan->id_dosen1 == $user->id) {
                                            $status = $item->status_dospem1;
                                        } elseif ($item->pengajuan->id_dosen2 == $user->id) {
                                            $status = $item->status_dospem2;
                                        } else {
                                            $status = 'Menunggu';
                                        }
                                    @endphp

                                    @if($status === 'Disetujui')
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif($status === 'Ditolak')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    @endif
                                </td>

                                {{-- Catatan --}}
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

                                {{-- Aksi --}}
                                <td>
                                    @if($status === 'Menunggu')
                                        <div class="d-flex gap-2">
                                            <button form="form-{{ $item->id_sempro }}" type="submit" name="status" value="Disetujui" class="btn btn-sm btn-success">Setujui</button>
                                            <button form="form-{{ $item->id_sempro }}" type="submit" name="status" value="Ditolak" class="btn btn-sm btn-danger">Tolak</button>
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
