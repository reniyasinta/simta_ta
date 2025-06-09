@extends('layouts.app')

@section('title', 'Jadwal Sidang')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Jadwal Sidang</h1>
        </div>

        <div class="card">
            <div class="card-body">
                @if ($jadwals->count())
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
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
                                @foreach ($jadwals as $index => $jadwal)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
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
                    <p class="text-muted">Belum ada jadwal sidang.</p>
                @endif
            </div>
        </div>
    </section>
</div>
@endsection
