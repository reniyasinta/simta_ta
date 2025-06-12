@extends('layouts.app')

@section('title', 'Dashboard Panitia')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Dashboard Panitia</h1>
        </div>

        {{-- QUICK MENU --}}
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <a href="{{ route('panitia.kuota.index') }}" class="card card-statistic-1 shadow-sm text-decoration-none">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-user-cog"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header"><h4>Manajemen Kuota</h4></div>
                        <div class="card-body">Kelola Kuota Bimbingan Dosen</div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-6">
                <a href="{{ route('panitia.pengajuan.index') }}" class="card card-statistic-1 shadow-sm text-decoration-none">
                    <div class="card-icon bg-success">
                        <i class="fas fa-file-signature"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header"><h4>Penentuan Dospem 2</h4></div>
                        <div class="card-body">Kelola Pengajuan Dospem 2</div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-6">
                <a href="{{ route('panitia.berkas.index') }}" class="card card-statistic-1 shadow-sm text-decoration-none">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-file-upload"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header"><h4>Upload Berkas</h4></div>
                        <div class="card-body">Kelola Berkas Sidang</div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-6">
                <a href="{{ route('jadwal.seminar.index') }}" class="card card-statistic-1 shadow-sm text-decoration-none">
                    <div class="card-icon bg-info">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header"><h4>Kelola Jadwal</h4></div>
                        <div class="card-body">Seminar, Sidang & Yudisium</div>
                    </div>
                </a>
            </div>
        </div>

        {{-- PENGAJUAN TERBARU
        <div class="section mt-5">
            <div class="section-header">
                <h4>Pengajuan Dospem Terbaru</h4>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Mahasiswa</th>
                            <th>Judul</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengajuanTerbaru as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->kelompok->anggota1->mahasiswa->nama_mhs ?? '-' }}</td>
                            <td>{{ $item->judul ?? '-' }}</td>
                            <td>{{ $item->status ?? '-' }}</td>
                            <td>{{ $item->created_at->format('d-m-Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum Ada Pengajuan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div> --}}

    </section>
</div>
@endsection
