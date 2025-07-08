@extends('layouts.app')

@section('title', 'Kelompok')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Kelompok</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('home') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Kelompok</div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header">
                <h4>Data Kelompok</h4>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead class="text-center">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>NIM</th>
                                <th>Kelas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $mahasiswa = auth()->user()->mahasiswa;
                                $kelompok = $mahasiswa?->kelompok?->anggota ?? [];
                            @endphp

                            @forelse ($kelompok as $index => $anggota)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $anggota->nama_mhs }}</td>
                                    <td>{{ $anggota->nim_mhs }}</td>
                                    <td>{{ $anggota->kelas ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Belum ada anggota dalam kelompok.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if(!$mahasiswa || !$mahasiswa->kelompok)
                <div class="text-right mt-3">
                    <a href="{{ route('kelompok.create') }}" class="btn btn-primary">Buat Kelompok</a>
                </div>
                @endif
            </div>
        </div>
    </section>
</div>
@endsection
