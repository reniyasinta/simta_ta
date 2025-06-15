@extends('layouts.app')

@section('title', 'Monitoring Kuota Bimbingan')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Monitoring Kuota Bimbingan</h1>
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
                        <th>Nama Dosen</th>
                        <th>Prodi</th>
                        <th>Kuota P1</th>
                        <th>Kuota P2</th>
                        <th>Terpakai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dosenList as $index => $dosen)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $dosen->nama_dosen }}</td>
                        <td>{{ $dosen->prodi->nama_prodi ?? '-' }}</td>
                        <td>{{ $dosen->kuota_bimbingan }}</td>
                        <td>{{ $dosen->kuota_p2 }}</td>
                        <td>{{ $dosen->bimbingan_terpakai }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
