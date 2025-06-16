@extends('layouts.app')

@section('title', 'Monitoring Jadwal Seminar')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Monitoring Jadwal Seminar</h1>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Kelompok</th>
                        <th>Judul</th>
                        <th>Penguji</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jadwals as $index => $jadwal)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $jadwal->tanggal }}</td>
                        <td>{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
                        <td>{{ $jadwal->nama }}</td>
                        <td>{{ $jadwal->judul_ta }}</td>
                        <td>
                            {{ $jadwal->penguji1->name ?? '-' }},
                            {{ $jadwal->penguji2->name ?? '-' }},
                            {{ $jadwal->penguji3->name ?? '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
