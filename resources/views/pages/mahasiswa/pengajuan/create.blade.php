@extends('layouts.app')

@push('style')
<link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Pengajuan Dosen Pembimbing 1</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('home') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('pengajuan.index') }}">pengajuan dospem1</a></div>
                <div class="breadcrumb-item">Form Pengajuan Dosen</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    @include('layouts.alert')
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header">
                            <h4>Form Pengajuan</h4>
                            @if(session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif
                        </div>

                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('pengajuan.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label>Nama Anggota Kelompok</label>
                                    @foreach($mahasiswa->kelompok->anggota as $mhs)
                                        <input type="text" class="form-control mb-2" value="{{ $mhs->nama_mhs }}" readonly>
                                    @endforeach
                                </div>

                                <div class="form-group mb-3">
                                    <label for="judul_ta">Judul Tugas Akhir</label>
                                    <input type="text" name="judul_ta" class="form-control" required>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="proposal">Proposal (PDF)</label>
                                    <input type="file" name="proposal" class="form-control" accept="application/pdf" required>
                                </div>

                            <div class="form-group mb-4">
                                <label for="id_dosen1">Pilih Dosen Pembimbing</label>
                                <select name="id_dosen1" class="form-control selectric" required>
                                    <option value="">-- Pilih Dosen --</option>
                                    @foreach($dosenList as $dosen)
                                        <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
                                    <a href="{{ route('pengajuan.index') }}" class="btn btn-secondary">Kembali</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.selectric').selectric();
    });
</script>
@endpush
