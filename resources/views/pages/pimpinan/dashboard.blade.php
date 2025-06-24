@extends('layouts.app')

@section('title', 'Dashboard Pimpinan')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Selamat datang, {{ Auth::user()->name ?? 'User Tidak Ditemukan' }}!</h1>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary"><i class="fas fa-file-alt"></i></div>
                    <div class="card-wrap">
                        <div class="card-header"><h4>Pengajuan</h4></div>
                        <div class="card-body">{{ $totalPengajuan }}</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success"><i class="fas fa-users"></i></div>
                    <div class="card-wrap">
                        <div class="card-header"><h4>Surat</h4></div>
                        <div class="card-body">{{ $totalSurat }}</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-info"><i class="fas fa-calendar-alt"></i></div>
                    <div class="card-wrap">
                        <div class="card-header"><h4>Jadwal</h4></div>
                        <div class="card-body">{{ $totalJadwal }}</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning"><i class="fas fa-tasks"></i></div>
                    <div class="card-wrap">
                        <div class="card-header"><h4>Kuota</h4></div>
                        <div class="card-body">{{ $totalKuota }}</div>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>
@endsection
