@extends('layouts.app')

@section('title', 'ACC Draft Sidang')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>ACC Draft Sidang</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

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
                    @forelse($sidangList as $index => $sidang)
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
                                <a href="{{ asset($sidang->laporan_TA) }}" target="_blank" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                            @else
                                <span class="text-muted">Belum Upload</span>
                            @endif
                        </td>

                        <td>
                            @if ($sidang->id_dosen1 == auth()->user()->id)
                                {{ $sidang->status_draft_dosen1 ?? '-' }}
                            @elseif ($sidang->id_dosen2 == auth()->user()->id)
                                {{ $sidang->status_draft_dosen2 ?? '-' }}
                            @else
                                -
                            @endif
                        </td>

                        <td>
                            {{ $sidang->catatan_dosen ?? '-' }}
                        </td>

                        <td>
                            <form action="{{ route('dosen.sidang.updateStatusDraft', $sidang->id_sidang) }}" method="POST" id="form-{{ $sidang->id_sidang }}">
                                @csrf

                                <div class="form-group mb-1">
                                    <textarea name="catatan" class="form-control form-control-sm" rows="2" placeholder="Catatan (opsional)">{{ old('catatan', $sidang->catatan_dosen ?? '') }}</textarea>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" name="status_draft" value="Disetujui" class="btn btn-sm btn-success w-50">
                                        <i class="fas fa-check-circle"></i> ACC
                                    </button>
                                    <button type="submit" name="status_draft" value="Revisi" class="btn btn-sm btn-warning w-50">
                                        <i class="fas fa-edit"></i> Revisi
                                    </button>
                                </div>
                            </form>
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
    </section>
</div>
@endsection
