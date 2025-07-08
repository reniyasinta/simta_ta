@extends('layouts.app')

@section('title', 'Dashboard Pimpinan')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1 class="text-capitalize">Selamat datang, {{ Auth::user()->name ?? 'User Tidak Ditemukan' }}!</h1>
        </div>

        <div class="section-body">
            <div class="row">

                @php
                    $cards = [
                        [
                            'icon' => 'fas fa-file-alt',
                            'color' => 'primary',
                            'title' => 'Pengajuan',
                            'value' => $totalPengajuan,
                        ],
                        [
                            'icon' => 'fas fa-envelope-open-text',
                            'color' => 'success',
                            'title' => 'Surat Masuk',
                            'value' => $totalSurat,
                        ],
                        [
                            'icon' => 'fas fa-calendar-check',
                            'color' => 'info',
                            'title' => 'Jadwal',
                            'value' => $totalJadwal,
                        ],
                    ];
                @endphp

                @foreach ($cards as $card)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card card-statistic-1 shadow">
                        <div class="card-icon bg-{{ $card['color'] }}">
                            <i class="{{ $card['icon'] }}"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{ $card['title'] }}</h4>
                            </div>
                            <div class="card-body text-lg font-weight-bold">
                                {{ $card['value'] }}
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </section>
</div>
@endsection
