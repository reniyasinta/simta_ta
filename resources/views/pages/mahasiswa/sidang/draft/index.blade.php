@extends('layouts.app')

@section('title', 'Laporan TA Draft')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Laporan TA Draft</h1>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Upload Laporan Tugas Akhir</h4>
            </div>
            <div class="card-body">
                @if (!$sidang || $sidang->status_draft_dosen1 !== 'Disetujui' || $sidang->status_draft_dosen2 !== 'Disetujui')
                    <div class="mb-3 d-flex justify-content-end">
                        <a href="{{ route('mahasiswa.sidang.draft.create') }}" class="btn btn-primary">+ Upload Berkas</a>
                    </div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>File Draft</th>
                                <th>Lembar Konsultasi</th>
                                <th>Status Dospem 1</th>
                                <th>Status Dospem 2</th>
                                <th>Catatan Dospem 1</th>
                                <th>Catatan Dospem 2</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if ($sidang)
                            <tr>
                                <td>1</td>
                                <td><a href="{{ asset($sidang->laporan_TA) }}" target="_blank">Lihat</a></td>
                                <td><a href="{{ asset($sidang->lembar_konsultasi) }}" target="_blank">Lihat</a></td>
                                <td>{{ $sidang->status_draft_dosen1 ?? '-' }}</td>
                                <td>{{ $sidang->status_draft_dosen2 ?? '-' }}</td>
                                <td>{{ $sidang->catatan_draft_dosen1 ?? '-' }}</td>
                                <td>{{ $sidang->catatan_draft_dosen2 ?? '-' }}</td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="7" class="text-center text-danger">Belum ada data sidang.</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
