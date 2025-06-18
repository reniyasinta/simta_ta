@extends('layouts.app')

@section('title', 'Undangan ' . ucfirst($jenis))

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Undangan {{ ucfirst($jenis) }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('home') }}">Dashboard</a></div>
                <div class="breadcrumb-item active">Undangan {{ ucfirst($jenis) }}</div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Card --}}
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Undangan {{ ucfirst($jenis) }}</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Ruangan</th>
                                <th>Penguji</th>
                                <th>Preview Berkas</th>
                                <th>Upload</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @forelse ($jadwals as $jadwal)
                                @foreach (['penguji1', 'penguji2', 'penguji3'] as $key)
                                    @php
                                        $penguji = $jadwal->$key;
                                        $existingUndangan = \App\Models\Undangan::where('jadwal_id', $jadwal->id)
                                            ->where('penguji_id', $penguji?->id)
                                            ->where('jenis_acara', $jenis)
                                            ->first();
                                    @endphp
                                    @if ($penguji)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}</td>
                                            <td>{{ $jadwal->ruangan }}</td>
                                            <td>{{ $penguji->name }}</td>
                                            <td>
                                                @if ($existingUndangan)
                                                    <a href="{{ Storage::url($existingUndangan->file_path) }}" target="_blank">Lihat</a>
                                                @else
                                                    <span class="text-muted">Belum ada berkas</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('mahasiswa.undangan.create', [
                                                    'jadwal_id' => $jadwal->id,
                                                    'penguji_id' => $penguji->id,
                                                    'jenis_acara' => $jenis
                                                ]) }}" class="btn btn-sm btn-primary">Upload</a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada jadwal {{ $jenis }} untuk Anda.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{-- End Card --}}

    </section>
</div>
@endsection
