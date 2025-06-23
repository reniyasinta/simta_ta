@extends('layouts.app')

@section('title', 'Dashboard Panitia')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Dashboard Panitia</h1>
        </div>

        <div class="row">
            {{-- Total Mahasiswa --}}
            <div class="col-md-4">
                <div class="card card-statistic-1 shadow">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Mahasiswa</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalMahasiswa }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Dosen --}}
            <div class="col-md-4">
                <div class="card card-statistic-1 shadow">
                    <div class="card-icon bg-info">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Dosen</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalDosen }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Jumlah Kelompok Lengkap --}}
            <div class="col-md-4">
                <div class="card card-statistic-1 shadow">
                    <div class="card-icon bg-success">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Jumlah Kelompok</h4>
                        </div>
                        <div class="card-body">
                            {{ $jumlahKelompokLengkap }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
