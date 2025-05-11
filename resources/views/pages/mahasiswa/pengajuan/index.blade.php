@extends('layouts.app')

@section('main')
<div class="container-fluid py-5">
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Pengajuan Dosen Pembimbing 1</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ url('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">pengajuan dospem1</div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Anggota Kelompok</th>
                            <th>Dosen Pembimbing</th>
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
                                <td>{{ $item->dosen1->nama_dosen ?? '-' }}</td>
                                <td>{{ $item->judul_ta }}</td>
                                <td>
                                    <a href="{{ asset('storage/proposal/' . $item->proposal) }}" target="_blank" class="btn btn-sm btn-link">Lihat</a>
                                </td>
                                <td>
                                    @if($item->status == 'Diterima')
                                        <span class="badge bg-success">ACC</span>
                                    @elseif($item->status == 'Ditolak')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    @endif
                                </td>
                                <td>{{ $item->keterangan ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada pengajuan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="text-right mt-3">
                    <a href="{{ route('pengajuan.create') }}" class="btn btn-primary">Pengajuan Dospem1</a>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
