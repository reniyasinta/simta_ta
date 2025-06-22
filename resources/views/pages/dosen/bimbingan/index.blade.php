@extends('layouts.app')

@section('title', 'Data Mahasiswa Bimbingan')

{{-- @push('style') --}}
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
{{-- @endpush --}}

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Data Mahasiswa Bimbingan</h1>
        </div>

        <div class="section-body">
            {{-- Pencarian Global --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <input type="text" id="searchInput" class="form-control" placeholder="Cari Mahasiswa / Prodi..." />
                </div>
            </div>

 <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 text-primary">Informasi Kuota Bimbingan</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <strong>Kuota Bimbingan:</strong> {{ $kuota }} &nbsp; | &nbsp;
                        <strong>Jumlah Bimbingan Aktif:</strong> {{ $totalBimbingan }}
                    </div>
                </div>
 </div>
            {{-- Mahasiswa Bimbingan 1 --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 text-primary">Mahasiswa Bimbingan 1</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table-bimbingan1" class="table table-bordered table-striped">
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
                                @php $no1 = 1; @endphp
                                @foreach($sebagaiPembimbing1 as $item)
                                    @foreach($item->kelompok->anggota as $mhs)
                                    <tr class="search-item">
                                        <td>{{ $no1++ }}</td>
                                        <td>{{ $mhs->nama_mhs }}</td>
                                        <td>{{ $mhs->prodi->nama_prodi ?? '-' }}</td>
                                        <td>{{ $item->judul_ta ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('dosen.mahasiswa.show', $mhs->id_mhs) }}" class="btn btn-sm btn-success">
                                                <i class="fas fa-user"></i> Profil
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Mahasiswa Bimbingan 2 --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 text-primary">Mahasiswa Bimbingan 2</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table-bimbingan2" class="table table-bordered table-striped">
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
                                @php $no2 = 1; @endphp
                                @foreach($sebagaiPembimbing2 as $item)
                                    @foreach($item->kelompok->anggota as $mhs)
                                    <tr class="search-item">
                                        <td>{{ $no2++ }}</td>
                                        <td>{{ $mhs->nama_mhs }}</td>
                                        <td>{{ $mhs->prodi->nama_prodi ?? '-' }}</td>
                                        <td>{{ $item->judul_ta ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('dosen.mahasiswa.show', $mhs->id_mhs) }}" class="btn btn-sm btn-success">
                                                <i class="fas fa-user"></i> Profil
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<script>
   $(document).ready(function () {
    // Pencarian Global untuk Bimbingan 1 dan 2
    $('#searchInput').on('keyup', function () {
        var value = $(this).val().toLowerCase();
        $(".search-item").filter(function () {
            var rowText = $(this).text().toLowerCase();
            $(this).toggle(rowText.indexOf(value) > -1);
        });
    });
});
</script>
@endpush
