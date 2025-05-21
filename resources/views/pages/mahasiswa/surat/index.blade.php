@extends('layouts.app')

@section('title', 'Suart Penelitian Mahasiswa')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Riwayat Pengajuan Surat Penelitian</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ url('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Surat</div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="d-flex justify-content-end mb-3">
                <a href="{{ route('mahasiswa.surat.create') }}" class="btn btn-primary">Ajukan Baru</a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped mt-3">
                    <thead>
                        <tr>
                            <th>Judul TA</th>
                            <th>Tujuan</th>
                            <th>Perihal</th>
                            <th>Dosen Pembimbing</th>
                            <th>Status</th>
                            <th>Surat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suratList as $surat)
                            <tr>
                                <td>{{ $surat->judul_ta }}</td>
                                <td>{{ $surat->tujuan }}</td>
                                <td>{{ $surat->perihal }}</td>
                                <td>{{ $surat->dosen_pembimbing }}</td>
                                <td class="text-capitalize">{{ $surat->status }}</td>
                                <td>
                                    @if($surat->file_surat)
                                    <a href="{{ route('mahasiswa.surat.download', $surat->id_surat) }}" class="text-success">Download</a>
                                    @else
                                        <span class="text-muted">Belum tersedia</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada pengajuan surat</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </section>
    </div>
</div>
@endsection
