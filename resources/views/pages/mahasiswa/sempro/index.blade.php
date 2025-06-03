@extends('layouts.app')

@section('title', 'Berkas SEMPRO')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Berkas SEMPRO</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- TABEL --}}
        <div class="card">
            <div class="card-header">
                <h4>Daftar Berkas SEMPRO</h4>
            </div>

            <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kelompok</th>
                        <th>Judul TA</th>
                        <th>Jenis</th>
                        <th>Preview</th>
                        <th>Upload</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Form Persetujuan --}}
                    <tr>
                        <td>1</td>
                            <td>
                                <ul style="padding-left: 16px;">
                                    @if($pengajuan->kelompok->anggota1)
                                        <li>{{ $pengajuan->kelompok->anggota1->mahasiswa->nama_mhs ?? '-' }} ({{ $pengajuan->kelompok->anggota1->mahasiswa->nim_mhs ?? '-' }})</li>
                                    @endif
                                    @if($pengajuan->kelompok->anggota2)
                                        <li>{{ $pengajuan->kelompok->anggota2->mahasiswa->nama_mhs ?? '-' }} ({{ $pengajuan->kelompok->anggota2->mahasiswa->nim_mhs ?? '-' }})</li>
                                    @endif
                                    @if($pengajuan->kelompok->anggota3)
                                        <li>{{ $pengajuan->kelompok->anggota3->mahasiswa->nama_mhs ?? '-' }} ({{ $pengajuan->kelompok->anggota3->mahasiswa->nim_mhs ?? '-' }})</li>
                                    @endif
                                </ul>
                            </td>
                        <td>{{ $pengajuan->judul_ta ?? '-' }}</td>
                        <td>Form Persetujuan</td>
                        <td>
                            @if($sempro && $sempro->form_persetujuan_sempro)
                            <a href="{{ asset($sempro->form_persetujuan_sempro) }}" target="_blank" class="btn btn-sm btn-success">Lihat</a>
                            @else
                                <span class="text-muted">Belum ada</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('mahasiswa.sempro.uploadForm') }}" method="POST" enctype="multipart/form-data" class="d-inline">
                                @csrf
                                <div class="input-group input-group-sm">
                                    <input type="file" name="form_persetujuan_sempro" class="form-control" required>
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary" title="Upload">
                                            <i class="fas fa-upload"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </td>
                       <td>
                        @if($sempro && $sempro->form_persetujuan_sempro)
                            <form action="{{ route('mahasiswa.sempro.deleteForm') }}" method="POST" onsubmit="return confirm('Hapus Form Persetujuan?')" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    </td>
                    </tr>

                    {{-- Hasil SEMPRO --}}
                    <tr>
                        <td>2</td>
                            <td>
                                <ul style="padding-left: 16px;">
                                    @if($pengajuan->kelompok->anggota1)
                                        <li>{{ $pengajuan->kelompok->anggota1->mahasiswa->nama_mhs ?? '-' }} ({{ $pengajuan->kelompok->anggota1->mahasiswa->nim_mhs ?? '-' }})</li>
                                    @endif
                                    @if($pengajuan->kelompok->anggota2)
                                        <li>{{ $pengajuan->kelompok->anggota2->mahasiswa->nama_mhs ?? '-' }} ({{ $pengajuan->kelompok->anggota2->mahasiswa->nim_mhs ?? '-' }})</li>
                                    @endif
                                    @if($pengajuan->kelompok->anggota3)
                                        <li>{{ $pengajuan->kelompok->anggota3->mahasiswa->nama_mhs ?? '-' }} ({{ $pengajuan->kelompok->anggota3->mahasiswa->nim_mhs ?? '-' }})</li>
                                    @endif
                                </ul>
                            </td>
                        <td>{{ $pengajuan->judul_ta ?? '-' }}</td>
                        <td>Hasil SEMPRO</td>
                        <td>
                            @if($sempro && $sempro->hasil_sempro)
                            <a href="{{ asset($sempro->lihat_sempro) }}" target="_blank" class="btn btn-sm btn-success">Lihat</a>
                            @else
                                <span class="text-muted">Belum ada</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('mahasiswa.sempro.uploadHasil') }}" method="POST" enctype="multipart/form-data" class="d-inline">
                                @csrf
                                <div class="input-group input-group-sm">
                                    <input type="file" name="hasil_sempro" class="form-control" required>
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary" title="Upload">
                                            <i class="fas fa-upload"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </td>
                        <td>
                            @if($sempro && $sempro->hasil_sempro)
                                <form action="{{ route('mahasiswa.sempro.deleteHasil') }}" method="POST" onsubmit="return confirm('Hapus Hasil SEMPRO?')" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
@endsection
