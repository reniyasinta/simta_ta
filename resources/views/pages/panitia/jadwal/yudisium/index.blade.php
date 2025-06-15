@extends('layouts.app')

@section('title', 'Jadwal Yudisium')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Jadwal Yudisium</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('panitia.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item active">Yudisium</div>
            </div>
        </div>

        {{-- Flash Message --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Layout Dua Kolom --}}
        <div class="row">

            {{-- Form Input di kiri --}}
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header"><h4>Input Jadwal Yudisium</h4></div>
                    <div class="card-body">
                        <form action="{{ route('panitia.jadwal.yudisium.store') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label for="tanggal">Tanggal Yudisium</label>
                                <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="jam_mulai">Jam Mulai</label>
                                <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="jam_selesai">Jam Selesai</label>
                                <input type="time" name="jam_selesai" id="jam_selesai" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="ruangan">Tempat</label>
                                <input type="text" name="ruangan" id="ruangan" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">Simpan Jadwal</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Daftar Jadwal di kanan --}}
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"><h4>Daftar Jadwal Yudisium</h4></div>
                    <div class="card-body">
                        @if($jadwals->count())
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tanggal</th>
                                        <th>Jam</th>
                                        <th>Tempat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jadwals as $jadwal)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d M Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ $jadwal->jam_selesai ? \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') : '-' }}</td>
                                        <td>{{ $jadwal->ruangan }}</td>
                                        <td>
                                            <a href="{{ route('panitia.jadwal.yudisium.edit', $jadwal->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('panitia.jadwal.yudisium.destroy', $jadwal->id) }}" method="POST" style="display:inline;">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                            <div class="alert alert-warning">Belum ada jadwal yudisium.</div>
                        @endif
                    </div>
                </div>
            </div>

        </div> {{-- End row --}}

    </section>
</div>
@endsection
