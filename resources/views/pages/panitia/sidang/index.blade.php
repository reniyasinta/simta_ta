@extends('layouts.app')

@section('title', 'Upload Berkas Sidang')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Upload Berkas Sidang Mahasiswa</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @foreach($sidangs as $sidang)
        <div class="card mb-4">
            <div class="card-body">
                <p><strong>Nama Mahasiswa:</strong> {{ $sidang->mahasiswa->nama_mhs ?? '-' }}</p>

                <form action="{{ route('panitia.sidang.upload', $sidang->id_sidang) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Laporan Akhir</label>
                        <input type="file" name="laporan_akhir" class="form-control mb-2">
                    </div>
                    <div class="form-group">
                        <label>Lembar Konsultasi</label>
                        <input type="file" name="lembar_konsultasi" class="form-control mb-2">
                    </div>
                    <div class="form-group">
                        <label>Hasil Sidang</label>
                        <input type="file" name="hasil_sidang" class="form-control mb-2">
                    </div>
                    <button type="submit" class="btn btn-primary">Upload Berkas</button>
                </form>

                <hr>
                <p><strong>Berkas Tersimpan:</strong></p>
                <ul>
                    @if($sidang->laporan_akhir)
                        <li><a href="{{ asset($sidang->laporan_akhir) }}" target="_blank">Laporan Akhir</a></li>
                    @endif
                    @if($sidang->lembar_konsultasi)
                        <li><a href="{{ asset($sidang->lembar_konsultasi) }}" target="_blank">Lembar Konsultasi</a></li>
                    @endif
                    @if($sidang->hasil_sidang)
                        <li><a href="{{ asset($sidang->hasil_sidang) }}" target="_blank">Hasil Sidang</a></li>
                    @endif
                </ul>
            </div>
        </div>
        @endforeach
    </section>
</div>
@endsection
