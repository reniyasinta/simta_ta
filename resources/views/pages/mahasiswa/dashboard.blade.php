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

        {{-- === JADWAL === --}}
        <div class="section-body">
            <h2 class="section-title">Jadwal Seminar dan Sidang Anda</h2>
                <ul class="section-lead ps-4">
                    <li><i class="fas fa-check-circle text-success me-2"></i> Perhatikan jadwal seminar dan sidang yang telah ditentukan oleh panitia.</li>
                    <li><i class="fas fa-check-circle text-success me-2"></i> Pastikan Anda hadir tepat waktu.</li>
                    <li><i class="fas fa-check-circle text-success me-2"></i> Harap selalu memeriksa jadwal secara berkala demi kelancaran TA Anda.</li>
                </ul>
            <div class="row">
        {{-- Jadwal Seminar --}}
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="card card-statistic-2">
                <div class="card-icon shadow-primary bg-info">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4 class="text-dark">Jadwal Seminar</h4>
                    </div>
                    <div class="card-body">
                        @php
                            $seminar = $jadwals->where('jenis_acara', 'seminar')->sortByDesc('tanggal')->first();
                        @endphp
                        @if ($seminar)
                            <div class="small text-dark">
                                <strong>Tanggal :</strong> {{ \Carbon\Carbon::parse($seminar->tanggal)->translatedFormat('d M Y') }}
                            </div>
                        @else
                            <p class="mb-0 small text-dark">Jadwal belum tersedia.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Jadwal Sidang --}}
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="card card-statistic-2">
                <div class="card-icon shadow-primary bg-warning">
                    <i class="fas fa-balance-scale"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4 class="text-dark">Jadwal Sidang</h4>
                    </div>
                    <div class="card-body">
                        @php
                            $sidang = $jadwals->where('jenis_acara', 'sidang')->sortByDesc('tanggal')->first();
                        @endphp
                        @if ($sidang)
                            <div class="small text-dark">
                                <strong>Tanggal :</strong> {{ \Carbon\Carbon::parse($sidang->tanggal)->translatedFormat('d M Y') }}
                            </div>
                        @else
                            <p class="mb-0 small text-dark">Jadwal belum tersedia.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Jadwal Yudisium --}}
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="card card-statistic-2">
                <div class="card-icon shadow-primary bg-primary">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4 class="text-dark">Jadwal Yudisium</h4>
                    </div>
                    <div class="card-body">
                        @php
                            $yudisium = $jadwals->where('jenis_acara', 'yudisium')->sortByDesc('tanggal')->first();
                        @endphp
                        @if ($yudisium)
                            <div class="small text-dark">
                                <strong>Tanggal :</strong> {{ \Carbon\Carbon::parse($yudisium->tanggal)->translatedFormat('d M Y') }}
                            </div>
                        @else
                            <p class="mb-0 small text-dark">Jadwal belum tersedia.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
            </div>
        </div>
        {{-- === PROFIL DOSEN === --}}
        <div class="section-body">
            <h2 class="section-title">Profil Dosen dan Kuota Bimbingan</h2>
                <ul class="section-lead ps-4">
                    <li><i class="fas fa-check-circle text-success me-2"></i> Bagi mahasiswa harap memperhatikan jumlah kuota bimbingan.</li>
                    <li><i class="fas fa-check-circle text-success me-2"></i> Pastikan menyelesaikan pengajuan dosen pembimbing dengan baik.</li>
                    <li><i class="fas fa-check-circle text-success me-2"></i> Jika ada kesulitan dalam pengajuan, silakan hubungi panitia.</li>
                </ul>
            @if ($dosens->count())
                <div class="row">
                    @foreach ($dosens as $dosen)
                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                        <div class="card profile-widget">
                            <div class="profile-widget-header d-flex flex-column align-items-center p-4">
                                <img alt="image"
                                    src="{{ $dosen->foto ? asset('uploads/foto_dosen/' . $dosen->foto) : asset('img/avatar/avatar-1.png') }}"
                                    class="rounded-circle"
                                    style="object-fit: cover; width: 100px; height: 100px;">

                                <div class="mt-3 text-center">
                                    <div class="text-muted small">Kuota Bimbingan</div>
                                    <div class="h6 font-weight-bold">{{ $dosen->kuota_terpakai }}/{{ $dosen->kuota_total ?? '-' }}</div>
                                </div>
                            </div>

                            <div class="profile-widget-description">
                                <h5 class="mb-2 font-weight-bold text-center">{{ $dosen->nama_dosen }}</h5>
                                <table class="table table-sm table-borderless mb-0">
                                    <tr>
                                        <th style="width: 120px;">NIP</th>
                                        <td>: {{ $dosen->nip_dosen }}</td>
                                    </tr>
                                    <tr>
                                        <th>Keahlian</th>
                                        <td>: {{ $dosen->keahlian ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>: {{ $dosen->user->email ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>No HP</th>
                                        <td>: {{ $dosen->no_telp ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Prodi</th>
                                        <td>: {{ $dosen->prodi->nama_prodi ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-warning">Belum ada data dosen yang tersedia.</div>
            @endif
        </div>
    </section>
</div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
@endpush
