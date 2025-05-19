@extends('layouts.app')

@push('style')
<link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
@endpush

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Daftar Pengajuan Dosen Pembimbing</h1>
        </div>

        <div class="section-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Dosen Pembimbing 1</th>
                        <th>Judul TA</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                        <th>Proposal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuan as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->dosen1->dosen->nama_dosen ?? '-' }}</td>
                            <td>{{ $item->judul_ta }}</td>
                            <td>
                                @if($item->status === 'Diterima')
                                    <span class="badge bg-success">{{ $item->status }}</span>
                                @elseif($item->status === 'Ditolak')
                                    <span class="badge bg-danger">{{ $item->status }}</span>
                                @else
                                    <span class="badge bg-warning text-dark">{{ $item->status }}</span>
                                @endif
                            </td>
                            <td>{{ $item->keterangan ?? '-' }}</td>
                            <td>
                                @if($item->proposal)
                                    <a href="{{ asset('storage/' . $item->proposal) }}" target="_blank">Lihat</a>
                                @else
                                    <span class="text-muted">Belum ada</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada pengajuan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
@endpush
