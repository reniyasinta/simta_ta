@extends('layouts.app')

@section('title', 'Manajemen Kuota Dosen')

@push('style')
<link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
@endpush

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Manajemen Kuota Dosen</h1>
        </div>

        <div class="section-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Dosen</th>
                            <th>Prodi</th>
                            <th>Kuota</th>
                            <th>Terisi</th>
                            <th>Sisa</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dosenList as $key => $dosen)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $dosen->nama_dosen }}</td>
                            <td>{{ $dosen->prodi->nama_prodi ?? '-' }}</td>
                            <td>
                                <form action="{{ route('panitia.kuota.update', $dosen->id_dosen) }}" method="POST" class="form-inline">
                                    @csrf
                                    <input type="number" name="kuota_bimbingan" value="{{ $dosen->kuota_bimbingan }}" class="form-control form-control-sm" style="width: 80px;" min="0">
                            </td>
                            <td>{{ $dosen->bimbingan_terpakai }}</td>
                            <td>
                                @php
                                    $sisa = ($dosen->kuota_bimbingan ?? 0) - $dosen->bimbingan_terpakai;
                                    $warna = 'bg-success';

                                    if ($sisa <= 2 && $sisa > 0) {
                                        $warna = 'bg-warning text-dark';
                                    } elseif ($sisa <= 0) {
                                        $warna = 'bg-danger';
                                    }
                                @endphp
                                <span class="badge {{ $warna }}">
                                    {{ $sisa }}
                                </span>
                            </td>
                            <td>
                                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach

                        @if($dosenList->isEmpty())
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data dosen.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
@endsection
