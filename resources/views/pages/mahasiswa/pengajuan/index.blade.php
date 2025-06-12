@extends('layouts.app')

@section('title', 'Pengajuan Dosen Pembimbing 1')

@push('style')
<link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
@endpush

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Pengajuan Dosen Pembimbing 1</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('home') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Pengajuan Dospem 1</div>
            </div>
        </div>

        {{-- Alert --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(isset($error))
            <div class="alert alert-warning">{{ $error }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Anggota Kelompok</th>
                        <th>Dosen Pembimbing 1</th>
                        <th>Dosen Pembimbing 2</th>
                        <th>Judul TA</th>
                        <th>Proposal</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuan as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <ul>
                                    @foreach($item->kelompok->anggota as $mhs)
                                        <li>{{ $mhs->nama_mhs }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>{{ $item->dosen1->dosen->nama_dosen ?? '-' }}</td>
                            <td>{{ $item->dosen2->dosen->nama_dosen ?? '-' }}</td>
                            <td>{{ $item->judul_ta }}</td>
                            <td>
                                @if ($item->proposal)
                                    <a href="{{ asset('storage/proposal/' . $item->proposal) }}" target="_blank">Lihat</a>
                                @else
                                    <span class="text-muted">Belum ada</span>
                                @endif
                            </td>
                            <td>
                                @if($item->status == 'Diterima')
                                    <span class="badge bg-success text-white">ACC</span>
                                @elseif($item->status == 'Ditolak')
                                    <span class="badge bg-danger text-white">Ditolak</span>
                                @else
                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                @endif
                            </td>
                            <td>{{ $item->keterangan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada pengajuan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if (!isset($error))
                @php
                    $adaMenunggu = $pengajuan->contains(function ($item) {
                        return $item->status === 'Menunggu';
                    });

                    $adaDiterima = $pengajuan->contains(function ($item) {
                        return $item->status === 'Diterima';
                    });
                @endphp

                @if (!$adaMenunggu && !$adaDiterima)
                    <div class="text-right mt-3">
                        <a href="{{ route('pengajuan.create') }}" class="btn btn-primary">Pengajuan Dospem1</a>
                    </div>
                @endif
            @endif

        </div>
    </section>
</div>
@endsection
