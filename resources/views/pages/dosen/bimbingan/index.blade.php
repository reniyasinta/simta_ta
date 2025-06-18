

@extends('layouts.app')

@section('title', 'Data Mahasiswa Bimbingan')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Data Mahasiswa Bimbingan</h1>
        </div>

        <div class="section-body">

            {{-- Filter Prodi --}}
            <select name="prodi" class="form-control mr-2" onchange="this.form.submit()">
                <option value="">-- Semua Prodi --</option>
                @foreach ($listProdi as $prodi)
                    <option value="{{ $prodi->id }}" {{ request('prodi') == $prodi->id ? 'selected' : '' }}>
                        {{ $prodi->nama_prodi }}
                    </option>
                @endforeach
            </select>

            {{-- Info Kuota --}}
            <div class="mb-4">
                <div class="alert alert-info mb-2">
                    <strong>Kuota Bimbingan:</strong> {{ $kuota }} &nbsp; | &nbsp;
                    <strong>Jumlah Bimbingan Aktif:</strong> {{ $totalBimbingan }}
                </div>
            </div>

 {{-- Section Pembimbing 1 --}}
<div class="card shadow mb-4">
    <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center">
        <h4 class="mb-0 font-weight-bold text-primary">Mahasiswa Bimbingan 1</h4>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Mahasiswa</th>
                        <th>Prodi</th>
                        <th>Judul TA</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sebagaiPembimbing1 as $key => $item)
                        @foreach($item->kelompok->anggota as $mhs)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $mhs->nama_mhs }}</td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ $mhs->prodi->nama_prodi ?? '-' }}
                                    </span>
                                </td>
                                <td>{{ $item->judul_ta }}</td>
                                <td>
                                    <a href="{{ route('dosen.mahasiswa.show', $mhs->id_mhs) }}" class="btn btn-sm btn-success">
                                        <i class="fas fa-user"></i> Lihat Profil
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Tidak ada bimbingan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

            {{-- Section Pembimbing 2 --}}
<div class="card shadow mb-4">
    <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center">
        <h4 class="mb-0 font-weight-bold text-primary">Mahasiswa bimbingan 2</h4>
    </div>


                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>Prodi</th>
                                    <th>Judul TA</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sebagaiPembimbing2 as $key => $item)
                                    @foreach($item->kelompok->anggota as $mhs)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $mhs->nama_mhs }}</td>
                                            <td>
                                                <span class="badge badge-info">
                                                    {{ $mhs->prodi->nama_prodi ?? '-' }}
                                                </span>
                                            </td>
                                            <td>{{ $item->judul_ta }}</td>
                                            <td>
                                                <a href="{{ route('dosen.mahasiswa.show', $mhs->id_mhs) }}" class="btn btn-sm btn-success">
                                                    <i class="fas fa-user"></i> Lihat Profil
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">Tidak ada bimbingan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection
