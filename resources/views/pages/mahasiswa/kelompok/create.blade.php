@extends('layouts.app')

@section('main')
<div class="container-fluid py-5">
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Buat Kelompok</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ url('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('kelompok.index') }}">Kelompok</a></div>
                    <div class="breadcrumb-item">Buat kelompok</div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('kelompok.store') }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label>Anggota 1 (Anda)</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label for="anggota_2_id">Anggota 2 (opsional)</label>
                            <select name="anggota_2_id" class="form-control">
                                <option value="">-- Pilih Anggota 2 --</option>
                                @foreach ($mahasiswas as $mhs)
                                    <option value="{{ $mhs->id }}">{{ $mhs->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="anggota_3_id">Anggota 3 (opsional)</label>
                            <select name="anggota_3_id" class="form-control">
                                <option value="">-- Pilih Anggota 3 --</option>
                                @foreach ($mahasiswas as $mhs)
                                    <option value="{{ $mhs->id }}">{{ $mhs->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Buat Kelompok</button>
                            <a href="{{ route('kelompok.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>

        </section>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const anggota2 = document.querySelector('[name="anggota_2_id"]');
        const anggota3 = document.querySelector('[name="anggota_3_id"]');

        function disableSameOptions() {
            let selected2 = anggota2.value;
            let selected3 = anggota3.value;

            anggota2.querySelectorAll('option').forEach(opt => opt.disabled = false);
            anggota3.querySelectorAll('option').forEach(opt => opt.disabled = false);

            if (selected3) {
                anggota2.querySelector(`option[value="${selected3}"]`)?.setAttribute('disabled', 'disabled');
            }
            if (selected2) {
                anggota3.querySelector(`option[value="${selected2}"]`)?.setAttribute('disabled', 'disabled');
            }
        }

        anggota2.addEventListener('change', disableSameOptions);
        anggota3.addEventListener('change', disableSameOptions);
    });
</script>
@endsection
