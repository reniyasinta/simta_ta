@extends('layouts.app')

@section('title', 'Laporan TA Draft')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Laporan TA Draft</h1>
        </div>

        <div class="mb-3 d-flex justify-content-end">
            <a href="{{ route('mahasiswa.sidang.draft.create') }}" class="btn btn-primary">+ Upload Berkas</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Proposal TA</th>
                        <th>Lembar Konsultasi</th>
                        <th>Status Dospem 1</th>
                        <th>Status Dospem 2</th>
                        <th>Catatan Dospem 1</th>
                        <th>Catatan Dospem 2</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>

                        <td>
                            @if ($sidang && $sidang->laporan_TA)
                                <a href="{{ asset($sidang->laporan_TA) }}" target="_blank">Lihat</a>
                            @else
                                Belum Upload
                            @endif
                        </td>

                        <td>
                            @if ($sidang && $sidang->lembar_konsultasi)
                                <a href="{{ asset($sidang->lembar_konsultasi) }}" target="_blank">Lihat</a>
                            @else
                                Belum Upload
                            @endif
                        </td>

                        <td>
                            {{ $sidang->status_draft_dosen1 ?? '-' }}
                        </td>

                        <td>
                            {{ $sidang->status_draft_dosen2 ?? '-' }}
                        </td>

                        <td>
                            {{ $sidang->catatan_draft_dosen1 ?? '-' }}
                        </td>

                        <td>
                            {{ $sidang->catatan_draft_dosen2 ?? '-' }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
