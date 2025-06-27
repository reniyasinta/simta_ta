@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

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
                {{-- Seminar --}}
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <a href="{{ route('mahasiswa.jadwal.seminar') }}" style="text-decoration: none;">
                        <div class="card card-statistic-2">
                            <div class="card-icon shadow-primary bg-info">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header"><h4 class="text-dark">Jadwal Seminar</h4></div>
                                <div class="card-body">
                                    @php
                                        $seminar = $jadwals->where('jenis_acara', 'seminar')->sortByDesc('tanggal')->first();
                                    @endphp
                                    @if ($seminar)
                                        <div class="small text-dark"><strong>Tanggal :</strong> {{ \Carbon\Carbon::parse($seminar->tanggal)->translatedFormat('d M Y') }}</div>
                                    @else
                                        <p class="mb-0 small text-dark">Jadwal belum tersedia.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Sidang --}}
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <a href="{{ route('mahasiswa.jadwal.sidang') }}" style="text-decoration: none;">
                        <div class="card card-statistic-2">
                            <div class="card-icon shadow-primary bg-warning">
                                <i class="fas fa-balance-scale"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header"><h4 class="text-dark">Jadwal Sidang</h4></div>
                                <div class="card-body">
                                    @php
                                        $sidang = $jadwals->where('jenis_acara', 'sidang')->sortByDesc('tanggal')->first();
                                    @endphp
                                    @if ($sidang)
                                        <div class="small text-dark"><strong>Tanggal :</strong> {{ \Carbon\Carbon::parse($sidang->tanggal)->translatedFormat('d M Y') }}</div>
                                    @else
                                        <p class="mb-0 small text-dark">Jadwal belum tersedia.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Yudisium --}}
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="card card-statistic-2">
                        <div class="card-icon shadow-primary bg-primary">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header"><h4 class="text-dark">Jadwal Yudisium</h4></div>
                            <div class="card-body">
                                @php
                                    $yudisium = $jadwals->where('jenis_acara', 'yudisium')->sortByDesc('tanggal')->first();
                                @endphp
                                @if ($yudisium)
                                    <div class="small text-dark"><strong>Tanggal :</strong> {{ \Carbon\Carbon::parse($yudisium->tanggal)->translatedFormat('d M Y') }}</div>
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
        <div class="section-body mt-5">
            <h2 class="section-title">Profil Dosen dan Kuota Bimbingan</h2>
            <ul class="section-lead ps-4">
                <li><i class="fas fa-check-circle text-success me-2"></i> Perhatikan jumlah kuota bimbingan dosen.</li>
                <li><i class="fas fa-check-circle text-success me-2"></i> Klik tombol detail untuk melihat informasi lengkap dosen.</li>
            </ul>

  @if ($dosens->count())
                <div class="row mt-3">
                    @foreach ($dosens as $dosen)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="d-flex align-items-center shadow-sm bg-white border rounded p-3 h-100">
                                <img src="{{ $dosen->foto ? asset('storage/uploads/foto_dosen/' . $dosen->foto) : asset('img/avatar/avatar-1.png') }}"
                                     alt="Foto Dosen"
                                     class="rounded-circle me-4"
                                     style="width: 50px; height: 50px; object-fit: cover; margin-right: 16px;">

                                <div class="flex-grow-1">
                                    <div class="fw-bold text-dark">{{ $dosen->nama_dosen }}</div>
                                    <div class="text-muted small">Kuota: {{ $dosen->kuota_terpakai }}/{{ $dosen->kuota_total ?? 0 }}</div>
                                </div>

                                <a href="{{ route('mahasiswa.dosen.show', $dosen->id_dosen) }}"
                                   class="btn btn-outline-primary btn-sm" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
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
