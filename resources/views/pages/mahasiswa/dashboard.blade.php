@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@push('style')
<style>
    .modal-backdrop.show {
        opacity: 0.3 !important;
        background-color: #ffffff !important;
    }

    .modal-content {
        background-color: #fff !important;
        color: #000000;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        z-index: 1055;
    }

    .modal-header .btn-close {
        background: none;
        border: none;
        font-size: 1.2rem;
    }

    .modal .table th {
        width: 100px;
    }

    .modal-title {
        font-weight: bold;
        color: #000;
    }

    .modal-header {
        border-bottom: 1px solid #dee2e6;
    }
</style>
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
                <div class="col-lg-4 col-md-6 col-sm-12">
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
                <div class="col-lg-4 col-md-6 col-sm-12">
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
                <div class="col-lg-4 col-md-6 col-sm-12">
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
                <li><i class="fas fa-check-circle text-success me-2"></i> Perhatikan jumlah kuota bimbingan dosen.</li>
                <li><i class="fas fa-check-circle text-success me-2"></i> Klik tombol detail untuk melihat informasi lengkap dosen.</li>
            </ul>

            @if ($dosens->count())
                <div class="row mt-3">
                    @foreach ($dosens as $dosen)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="d-flex align-items-center shadow-sm bg-white border rounded p-3 h-100">
                                <img src="{{ $dosen->foto ? asset('uploads/foto_dosen/' . $dosen->foto) : asset('img/avatar/avatar-1.png') }}"
                                     alt="Foto Dosen"
                                     class="rounded-circle me-4"
                                     style="width: 50px; height: 50px; object-fit: cover; margin-right: 16px;">

                                <div class="flex-grow-1">
                                    <div class="fw-bold text-dark">{{ $dosen->nama_dosen }}</div>
                                    <div class="text-muted small">Kuota: {{ $dosen->kuota_terpakai }}/{{ $dosen->kuota_total ?? 0 }}</div>
                                </div>

                                <button class="bg-transparent border-0 text-primary"
                                        data-toggle="modal"
                                        data-target="#modalDosen{{ $dosen->id_dosen }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Modal --}}
                        <div class="modal fade" id="modalDosen{{ $dosen->id_dosen }}" tabindex="-1"
                             aria-labelledby="modalDosenLabel{{ $dosen->id_dosen }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Detail Dosen</h5>
                                    </div>
                                    <div class="modal-body">
                                        <div class="text-center mb-3">
                                            <img src="{{ $dosen->foto ? asset('uploads/foto_dosen/' . $dosen->foto) : asset('img/avatar/avatar-1.png') }}"
                                                 class="rounded-circle"
                                                 style="width: 90px; height: 90px; object-fit: cover;">
                                        </div>
                                        <table class="table table-sm table-borderless">
                                            <tr><th style="width: 120px;">Nama</th><td>: {{ $dosen->nama_dosen }}</td></tr>
                                            <tr><th>NIP</th><td>: {{ $dosen->nip_dosen }}</td></tr>
                                            <tr><th>Keahlian</th><td>: {{ $dosen->keahlian ?? '-' }}</td></tr>
                                            <tr><th>Email</th><td>: {{ $dosen->user->email ?? '-' }}</td></tr>
                                            <tr><th>No HP</th><td>: {{ $dosen->no_telp ?? '-' }}</td></tr>
                                            <tr><th>Prodi</th><td>: {{ $dosen->prodi->nama_prodi ?? '-' }}</td></tr>
                                            <tr><th>Kuota</th><td>: {{ $dosen->kuota_bimbingan ?? '-' }}</td></tr>
                                        </table>

                                        {{-- Tombol Kembali --}}
                                        <div class="text-right mt-4">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                                        </div>
                                    </div>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
@endpush
