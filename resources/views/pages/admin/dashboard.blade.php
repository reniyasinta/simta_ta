@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Dashboard Admin</h1>
        </div>

        {{-- Welcome Message --}}
        <div class="alert alert-primary">
            Selamat datang, {{ Auth::user()->name }}! Hari ini tanggal {{ now()->format('d F Y') }}.
        </div>

            <div class="col-md-15">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning text-white">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Pengajuan Surat</h4>
                        </div>
                        <div class="card-body">
                            {{ $jumlahPengajuanSurat ?? 0 }}
                        </div>
                    </div>
                </div>
            </div>

        {{-- Pengajuan Surat Terbaru --}}
        <div class="card mt-4">
            <div class="card-header">
                <h4>Pengajuan Surat Terbaru</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Mahasiswa</th>
                                <th>Tujuan</th>
                                <th>Judul TA</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengajuanTerbaru as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->mahasiswa->nama_mhs ?? '-' }}</td>
                                    <td>{{ $item->tujuan ?? '-' }}</td>
                                    <td>{{ $item->judul_ta ?? '-' }}</td>
                                    <td>
                                        @if($item->status == 'selesai')
                                            <span class="badge badge-success">Selesai</span>
                                        @elseif($item->status == 'diproses')
                                            <span class="badge badge-warning">Diproses</span>
                                        @else
                                            <span class="badge badge-secondary">Menunggu</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.surat.edit', $item->id_surat) }}" class="btn btn-sm btn-primary">Upload</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada pengajuan surat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </section>
</div>
@endsection
