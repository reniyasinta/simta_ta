@extends('layouts.app')

@section('title', 'ACC Revisi Sidang')

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
                    <table class="table table-bordered table-striped">
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

                                <td>
                                    @php
                                        $latestRevisi = $sidang->revisi_laporan;
                                    @endphp
                                    @if($latestRevisi)
                                        <a href="{{ asset($latestRevisi) }}" target="_blank" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                    @else
                                        <span class="text-muted">Belum Upload</span>
                                    @endif

                                </td>

                                <td>
                                    @if ($sidang->penguji_1_id == auth()->user()->id)
                                        {{ $sidang->status_revisi_penguji_1 ?? '-' }}
                                    @elseif ($sidang->penguji_2_id == auth()->user()->id)
                                        {{ $sidang->status_revisi_penguji_2 ?? '-' }}
                                    @elseif ($sidang->penguji_3_id == auth()->user()->id)
                                        {{ $sidang->status_revisi_penguji_3 ?? '-' }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    @if ($sidang->penguji_1_id == auth()->user()->id)
                                        {{ $sidang->catatan_penguji_1 ?? '-' }}
                                    @elseif ($sidang->penguji_2_id == auth()->user()->id)
                                        {{ $sidang->catatan_penguji_2 ?? '-' }}
                                    @elseif ($sidang->penguji_3_id == auth()->user()->id)
                                        {{ $sidang->catatan_penguji_3 ?? '-' }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    @php
                                        $penguji_ke = null;
                                        if ($sidang->penguji_1_id == auth()->user()->id) {
                                            $penguji_ke = 1;
                                        } elseif ($sidang->penguji_2_id == auth()->user()->id) {
                                            $penguji_ke = 2;
                                        } elseif ($sidang->penguji_3_id == auth()->user()->id) {
                                            $penguji_ke = 3;
                                        }
                                    @endphp

                                    @if($penguji_ke)
                                    <form action="{{ route('dosen.sidang.updateStatusRevisi', [$sidang->id_sidang, $penguji_ke]) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        <select name="status_revisi" class="form-control form-control-sm d-inline w-auto mb-2" required>
                                            <option value="Menunggu">Menunggu</option>
                                            <option value="Revisi">Revisi</option>
                                            <option value="Disetujui">Disetujui</option>
                                        </select>

                                        <textarea name="catatan_revisi" class="form-control form-control-sm mb-2" placeholder="Catatan Revisi"></textarea>

                                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                    </form>
                                    @else
                                        <span class="text-muted">Anda bukan penguji untuk sidang ini.</span>
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
