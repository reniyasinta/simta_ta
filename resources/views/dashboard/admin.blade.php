@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Dashboard Admin</h1>
        <p>Selamat datang, {{ Auth::user()->name }}!</p>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Jadwal Mengajar</h4>
                    </div>
                    <div class="card-body">
                        <p>Berikut adalah jadwal mengajar Anda:</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Pengumuman</h4>
                    </div>
                    <div class="card-body">
                        <p>Pengumuman terbaru:</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
