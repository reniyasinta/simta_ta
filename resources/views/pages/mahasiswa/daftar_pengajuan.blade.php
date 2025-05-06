@extends('layouts.app')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
<div class="container">
    <h2>Daftar Pengajuan Dosen Pembimbing</h2>

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
                    <td>{{ $item->dosen1->nama }}</td>
                    <td>{{ $item->judul_ta }}</td>
                    <td>
                        @if($item->status == 'Diterima')
                            <span class="badge bg-success">{{ $item->status }}</span>
                        @elseif($item->status == 'Ditolak')
                            <span class="badge bg-danger">{{ $item->status }}</span>
                        @else
                            <span class="badge bg-warning text-dark">{{ $item->status }}</span>
                        @endif
                    </td>
                    <td>{{ $item->keterangan ?? '-' }}</td>
                    <td>
                        <a href="{{ asset('storage/' . $item->proposal) }}" target="_blank">Lihat</a>
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
@endsection
@push('scripts')
    <!-- JS Libraies -->

    <!-- Page Specific JS File -->
@endpush
