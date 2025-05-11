@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Selamat datang, {{ Auth::user()->mahasiswa?->nama_mhs ?? 'Mahasiswa Tidak Ditemukan' }}!</h1>
        </div>
        <div class="section-header">
            <h4>Jadwal Seminar / Sidang Anda</h4>
            @forelse($jadwals as $item)
                <div class="alert alert-info">
                    <strong>{{ strtoupper($item->jenis_acara) }}</strong><br>
                    Tanggal: {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y H:i') }}<br>
                    Tempat: {{ $item->tempat }}<br>
                    Judul: {{ $item->judul_ta }}
                </div>
            @empty
                <p>Tidak ada jadwal ditemukan.</p>
            @endforelse
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
