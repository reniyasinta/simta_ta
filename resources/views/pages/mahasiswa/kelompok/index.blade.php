@extends('layouts.app')

@section('title', 'Kelompok Saya')

@push('style')
<link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
@endpush

@section('main') {{-- ganti ke @section('content') kalau layout-nya pakai itu --}}
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Kelompok Saya</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ url('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Kelompok</div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="clearfix mb-3"></div>

            @forelse ($kelompok as $k)
                <h5>Kelompok #{{ $k->id_kelompok }}</h5>
                <div class="table-responsive mb-4">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>NIM</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($k->mahasiswa as $index => $mhs)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $mhs->nama_mhs }}</td>
                                    <td>{{ $mhs->nim_mhs }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <div class="text-right mt-3"> Belum ada anggota di kelompok ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
            @empty
                <div class="alert alert-info">
                    Belum ada kelompok yang dibuat.
                </div>
            @endforelse
               {{-- Cek apakah mahasiswa sudah tergabung dalam kelompok --}}
            @php
                $mahasiswa = auth()->user()->mahasiswa;
            @endphp

            @if(!$mahasiswa || !$mahasiswa->kelompok)
                <div class="text-right mt-3">
                    <a href="{{ route('kelompok.create') }}" class="btn btn-primary">Buat Kelompok</a>
                </div>
            @endif
        </section>
    </div>
@endsection
