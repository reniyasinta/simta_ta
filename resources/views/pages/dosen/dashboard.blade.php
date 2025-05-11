@extends('layouts.app')

@section('title', 'Dashboard Dosen')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')<div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Selamat datang, {{ Auth::user()->name}}!</h1>
            </div>
            <div class ="section-header">
                <h4>Jadwal Anda sebagai Penguji</h4>
                @foreach($jadwals as $jadwal)
                    @if ($jadwal->mahasiswa)
                        <div class="alert alert-warning">
                            <strong>{{ strtoupper($jadwal->jenis_acara) }}</strong> -
                            {{ $jadwal->mahasiswa->nama }} ({{ $jadwal->mahasiswa->nim }})<br>
                            Judul: {{ $jadwal->judul_ta }}<br>
                            Tanggal: {{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->translatedFormat('d F Y H:i') }}<br>
                            Tempat: {{ $jadwal->tempat }}
                        </div>
                    @endif
                @endforeach

            </div>
            <div class="section-body">
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->

    <!-- Page Specific JS File -->
@endpush
