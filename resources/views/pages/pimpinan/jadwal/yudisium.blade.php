@extends('layouts.app')

@section('title', 'Monitoring Jadwal Yudisium')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Monitoring Jadwal Yudisium</h1>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Ruangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jadwals as $index => $jadwal)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $jadwal->tanggal }}</td>
                        <td>{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
                        <td>{{ $jadwal->ruangan }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
