@extends('layouts.app')

@section('title', 'Monitoring Surat Mahasiswa')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Monitoring Surat Mahasiswa</h1>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Mahasiswa</th>
                        <th>Perihal</th>
                        <th>Judul</th>
                        <th>Dosen Pembimbing</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($suratList as $index => $surat)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            {{ $surat->mahasiswa->nama_mhs ?? '-' }}
                            ({{ $surat->mahasiswa->nim_mhs ?? '-' }})
                        </td>
                        <td>{{ $surat->perihal }}</td>
                        <td>{{ $surat->judul_ta ?? '-' }}</td>
                        <td>{{ $surat->dosen_pembimbing ?? '-' }}</td>
                        <td>{{ $surat->status }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
