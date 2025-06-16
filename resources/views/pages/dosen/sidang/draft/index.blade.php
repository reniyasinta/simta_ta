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
                            @php
                                $latestDraft = $sidang->draftUploads()->latest('uploaded_at')->first();
                            @endphp

                            @if($latestDraft)
                                <a href="{{ asset($latestDraft->file_path) }}" target="_blank" class="btn btn-sm btn-info">Lihat</a>
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
                                    $catatan = $sidang->catatan_draft_dosen1 ?? '-';
                                } elseif ($sidang->id_dosen2 == auth()->user()->id) {
                                    $catatan = $sidang->catatan_draft_dosen2 ?? '-';
                                }
                            @endphp
                            {{ $catatan }}
                        </td>

                        <td>
                            @if ($status == '-' || $status == 'Menunggu')
                            <form action="{{ route('dosen.sidang.updateStatusDraft', $sidang->id_sidang) }}" method="POST">
                                @csrf
                                <textarea name="catatan" class="form-control form-control-sm mb-2" placeholder="Catatan (opsional)"></textarea>
                                <div class="d-flex gap-2">
                                    <button type="submit" name="status_draft" value="Disetujui" class="btn btn-sm btn-success">Setuju</button>
                                    <button type="submit" name="status_draft" value="Revisi" class="btn btn-sm btn-warning">Revisi</button>
                                </div>
                            </form>
                            @else
                                <span class="badge badge-success">Sudah Dinilai</span>
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
    </section>
</div>
@endsection
