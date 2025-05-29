@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@push('style')
<style>
    .modal-backdrop.show {
        opacity: 0.3 !important; /* Lebih terang, tidak terlalu gelap */
        background-color: #ffffff !important;
    }

    .modal-content {
        background-color: #fff !important;
        color: #000000;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        z-index: 1055; /* pastikan lebih tinggi dari backdrop */
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
        <li><i class="fas fa-check-circle text-success me-2"></i> Perhatikan jumlah kuota bimbingan dosen.</li>
        <li><i class="fas fa-check-circle text-success me-2"></i> Klik tombol detail untuk melihat informasi lengkap dosen.</li>
    </ul>
@if ($dosens->count())
    <div class="d-flex flex-wrap gap-4 mt-3">
        @foreach ($dosens as $dosen)
            <div class="d-flex align-items-center shadow-sm bg-white border rounded px-3 py-2"
                 style="min-width: 320px; margin-right: 12px; margin-bottom: 16px;">

                {{-- Gambar Profil --}}
                <img src="{{ $dosen->foto ? asset('uploads/foto_dosen/' . $dosen->foto) : asset('img/avatar/avatar-1.png') }}"
                     alt="Foto Dosen"
                     class="rounded-circle"
                     style="width: 50px; height: 50px; object-fit: cover; margin-right: 16px;">

                {{-- Informasi Dosen --}}
                <div class="flex-grow-1">
                    <div class="fw-bold text-dark">{{ $dosen->nama_dosen }}</div>
                    <div class="text-muted small">Kuota: {{ $dosen->kuota_terpakai }}/{{ $dosen->kuota_total ?? 0 }}</div>
                </div>

                {{-- Tombol Detail --}}
                <button class="bg-transparent border-0 text-primary"
                        data-toggle="modal"
                        data-target="#modalDosen{{ $dosen->id_dosen }}">
                    <i class="fas fa-eye"></i>
                </button>
            </div>


                <!-- Modal -->
                <div class="modal fade" id="modalDosen{{ $dosen->id_dosen }}" tabindex="-1" aria-labelledby="modalDosenLabel{{ $dosen->id_dosen }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Detail Dosen</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <div class="modal-body">
                                <div class="text-center mb-3">
                                    <img src="{{ $dosen->foto ? asset('uploads/foto_dosen/' . $dosen->foto) : asset('img/avatar/avatar-1.png') }}"
                                         class="rounded-circle"
                                         style="width: 90px; height: 90px; object-fit: cover;">
                                </div>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th style="width: 120px;">Nama</th>
                                        <td>: {{ $dosen->nama_dosen }}</td>
                                    </tr>
                                    <tr>
                                        <th>NIP</th>
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
                                    <tr>
                                        <th>Kuota</th>
                                        <td>: {{ $dosen->kuota_bimbingan ?? '-'}}</td>
                                    </tr>
                                </table>
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

@endpush

