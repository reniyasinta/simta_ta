@extends('layouts.app')

@section('title', 'Jadwal Saya')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Jadwal Saya</h1>
        </div>

        <div class="card">
            <div class="card-header">
                <h4>Jadwal Seminar & Sidang</h4>
            </div>
            <div class="card-body">
                @php
                    $seminarSidang = $jadwals->whereIn('jenis_acara', ['seminar', 'sidang'])->sortByDesc('tanggal');
                @endphp

                @if ($seminarSidang->count())
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jenis</th>
                                    <th>Tanggal</th>
                                    <th>Jam</th>
                                    <th>Ruangan</th>
                                    <th>Judul TA</th>
                                    <th>Pembimbing 1</th>
                                    <th>Pembimbing 2</th>
                                    <th>Penguji 1</th>
                                    <th>Penguji 2</th>
                                    <th>Penguji 3</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($seminarSidang as $index => $jadwal)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ ucfirst($jadwal->jenis_acara) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d M Y') }}</td>
                                        <td>{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai ?? '-' }}</td>
                                        <td>{{ $jadwal->ruangan }}</td>
                                        <td>{{ $jadwal->judul_ta }}</td>
                                        <td>{{ $jadwal->pembimbing_1 }}</td>
                                        <td>{{ $jadwal->pembimbing_2 }}</td>
                                        <td>{{ $jadwal->penguji1->name ?? '-' }}</td>
                                        <td>{{ $jadwal->penguji2->name ?? '-' }}</td>
                                        <td>{{ $jadwal->penguji3->name ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">Belum ada jadwal seminar atau sidang.</p>
                @endif
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h4>Jadwal Yudisium</h4>
            </div>
            <div class="card-body">
                @php
                    $yudisium = $jadwals->where('jenis_acara', 'yudisium')->sortByDesc('tanggal')->first();
                @endphp

                @if ($yudisium)
                    <table class="table table-bordered">
                        <tr>
                            <th>Tanggal</th>
                            <td>{{ \Carbon\Carbon::parse($yudisium->tanggal)->translatedFormat('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th>Jam</th>
                            <td>{{ $yudisium->jam_mulai }} - {{ $yudisium->jam_selesai ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tempat</th>
                            <td>{{ $yudisium->ruangan }}</td>
                        </tr>
                    </table>
                @else
                    <p class="text-muted">Belum ada jadwal yudisium.</p>
                @endif
            </div>
        </div>

    </section>
</div>
@endsection
