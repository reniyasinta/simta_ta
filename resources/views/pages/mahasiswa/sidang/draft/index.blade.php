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
                        <th>File Draft</th>
                        <th>Lembar Konsultasi</th>
                        <th>Waktu Upload</th>
                        <th>Status Dospem 1</th>
                        <th>Status Dospem 2</th>
                        <th>Catatan Dospem 1</th>
                        <th>Catatan Dospem 2</th>
                    </tr>
                </thead>
                <tbody>

                @if ($sidang)
                    @foreach($sidang->uploads()->where('jenis_upload', 'draft')->orderBy('uploaded_at', 'desc')->get() as $index => $upload)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><a href="{{ asset($upload->file_path) }}" target="_blank">Lihat</a></td>
                            <td><a href="{{ asset($upload->file_path_2) }}" target="_blank">Lihat</a></td>
                            <td>{{ $upload->uploaded_at ?? '-' }}</td>
                            <td>{{ $sidang->status_draft_dosen1 ?? '-' }}</td>
                            <td>{{ $sidang->status_draft_dosen2 ?? '-' }}</td>
                            <td>{{ $sidang->catatan_draft_dosen1 ?? '-' }}</td>
                            <td>{{ $sidang->catatan_draft_dosen2 ?? '-' }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="8" class="text-center text-danger">Belum ada data sidang.</td>
                    </tr>
                @endif

                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
