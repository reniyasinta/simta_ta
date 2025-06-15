@extends('layouts.app')

@section('title', 'Pengajuan Pembimbing')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Pengajuan Pembimbing</h1>
        </div>

        <form method="get" class="mb-3">
            <div class="form-row">
                <div class="col-md-4">
                    <select name="prodi" class="form-control" onchange="this.form.submit()">
                        <option value="">Semua Prodi</option>
                        @foreach($prodis as $prodi)
                            <option value="{{ $prodi->id }}" {{ request('prodi') == $prodi->id ? 'selected' : '' }}>
                                {{ $prodi->nama_prodi }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kelompok</th>
                        <th>Judul</th>
                        <th>Dosen 1</th>
                        <th>Dosen 2</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pengajuanList as $key => $p)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>
                            <ul>
                            @foreach($p->kelompok->anggota as $mhs)
                                <li>{{ $mhs->nama_mhs }} ({{ $mhs->nim_mhs }})</li>
                            @endforeach
                            </ul>
                        </td>
                        <td>{{ $p->judul_ta }}</td>
                        <td>{{ $p->dosen1->dosen->nama_dosen ?? '-' }}</td>
                        <td>{{ $p->dosen2->dosen->nama_dosen ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
