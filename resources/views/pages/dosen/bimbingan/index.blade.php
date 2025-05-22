@extends('layouts.app')

@section('title', 'Data Mahasiswa Bimbingan')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Data Mahasiswa Bimbingan</h1>
        </div>

        <div class="section-body">
            <div class="mb-4">
                <h5>Kuota Bimbingan: {{ $kuota }}</h5>
                <h5>Jumlah Bimbingan Aktif: {{ $totalBimbingan }}</h5>
            </div>

            <div class="card">
                <div class="card-header"><h4>Dosen Pembimbing 1</h4></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>Judul TA</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sebagaiPembimbing1 as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <ul>
                                                @foreach($item->kelompok->anggota as $mhs)
                                                    <li>{{ $mhs->nama_mhs }}</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>{{ $item->judul_ta }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center">Tidak ada bimbingan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header"><h4>Dosen Pembimbing 2</h4></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>Judul TA</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sebagaiPembimbing2 as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <ul>
                                                @foreach($item->kelompok->anggota as $mhs)
                                                    <li>{{ $mhs->nama_mhs }}</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>{{ $item->judul_ta }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center">Tidak ada bimbingan.</td></tr>
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
