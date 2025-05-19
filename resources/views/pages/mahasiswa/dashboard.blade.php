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
                {{-- Jadwal Sosialisasi --}}
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="card card-statistic-2">
                        <div class="card-icon shadow-primary bg-primary">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4 class="text-dark">Jadwal Sosialisasi</h4>
                            </div>
                            <div class="card-body">
                                @php $sosialisasi = $jadwals->where('jenis_acara', 'sosialisasi'); @endphp
                                @forelse($sosialisasi as $item)
                                    <div class="alert alert-info">
                                        <strong>{{ strtoupper($item->jenis_acara) }}</strong><br>
                                        Tanggal: {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y H:i') }}<br>
                                        Tempat: {{ $item->tempat }}<br>
                                        Judul: {{ $item->judul_ta }}
                                    </div>
                                @empty
                                    <p class="mb-0 text-muted small">Jadwal belum ditentukan.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Jadwal Seminar --}}
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="card card-statistic-2">
                        <div class="card-icon shadow-primary bg-primary">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4 class="text-dark">Jadwal Seminar</h4>
                            </div>
                            <div class="card-body">
                                @php $seminar = $jadwals->where('jenis_acara', 'seminar'); @endphp
                                @forelse($seminar as $item)
                                    <div class="alert alert-info">
                                        <strong>{{ strtoupper($item->jenis_acara) }}</strong><br>
                                        Tanggal: {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y H:i') }}<br>
                                        Tempat: {{ $item->tempat }}<br>
                                        Judul: {{ $item->judul_ta }}
                                    </div>
                                @empty
                                    <p class="mb-0 text-muted small">Jadwal belum ditentukan.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Jadwal Sidang --}}
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="card card-statistic-2">
                        <div class="card-icon shadow-primary bg-primary">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4 class="text-dark">Jadwal Sidang</h4>
                            </div>
                            <div class="card-body">
                                @php $sidang = $jadwals->where('jenis_acara', 'sidang'); @endphp
                                @forelse($sidang as $item)
                                    <div class="alert alert-info">
                                        <strong>{{ strtoupper($item->jenis_acara) }}</strong><br>
                                        Tanggal: {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y H:i') }}<br>
                                        Tempat: {{ $item->tempat }}<br>
                                        Judul: {{ $item->judul_ta }}
                                    </div>
                                @empty
                                    <p class="mb-0 text-muted small">Jadwal belum ditentukan.</p>
                                @endforelse
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
                                <div class="profile-widget-header">
                                    <img alt="image"
                                         src="{{ $dosen->foto ? asset('uploads/foto_dosen/' . $dosen->foto) : asset('img/avatar/avatar-1.png') }}"
                                         class="rounded-circle profile-widget-picture"
                                         style="object-fit: cover; width: 100px; height: 100px;">
                                    <div class="profile-widget-items">
                                        <div class="profile-widget-item">
                                            <div class="profile-widget-item-label">Kuota Bimbingan TI</div>
                                            <div class="profile-widget-item-value">0/12</div>
                                        </div>
                                        <div class="profile-widget-item">
                                            <div class="profile-widget-item-label">Kuota Bimbingan SIKC</div>
                                            <div class="profile-widget-item-value">0/3</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="profile-widget-description">
                                    <h5 class="mb-2 font-weight-bold">{{ $dosen->nama_dosen }}</h5>
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
