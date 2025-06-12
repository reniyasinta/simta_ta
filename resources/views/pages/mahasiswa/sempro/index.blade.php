@extends('layouts.app')

@section('title', 'Berkas SEMPRO')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Berkas Sempro</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- TABEL --}}
        <div class="card">
            <div class="card-header">
                <h4>Daftar Berkas Sempro</h4>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis</th>
                            <th>Upload (PDF)</th>
                            <th>Status & Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                        {{-- 1. Laporan TA --}}
                        <tr>
                            <td>1</td>
                            <td>Laporan TA</td>
                            <td>
                                <form action="{{ route('mahasiswa.sempro.uploadLaporanTa') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="input-group input-group-sm">
                                        <input type="file" name="laporan_sempro" class="form-control" accept="application/pdf" required>
                                        <button type="submit" class="btn btn-primary" title="Upload">
                                            <i class="fas fa-upload"></i>
                                        </button>
                                    </div>
                                </form>
                            </td>
                            <td>
                                {{-- STATUS & CATATAN DOSPEM 1 --}}
                                Dospem 1:
                                @if($sempro && $sempro->status_laporan_ta_dospem1 == 'Disetujui')
                                    <span class="badge bg-success text-white mb-1">Acc</span>
                                @elseif($sempro && $sempro->status_laporan_ta_dospem1 == 'Revisi')
                                    <span class="badge bg-warning text-dark mb-1">Revisi</span>
                                @else
                                    <span class="text-muted mb-1">Menunggu</span>
                                @endif
                                <br>
                                <small><strong>Catatan:</strong> {{ $sempro->catatan_dospem1 ?? '-' }}</small>

                                {{-- STATUS & CATATAN DOSPEM 2 --}}
                                <br>Dospem 2:
                                @if($sempro && $sempro->status_laporan_ta_dospem2 == 'Disetujui')
                                    <span class="badge bg-success text-white mb-1">Acc</span>
                                @elseif($sempro && $sempro->status_laporan_ta_dospem2 == 'Revisi')
                                    <span class="badge bg-warning text-dark mb-1">Revisi</span>
                                @else
                                    <span class="text-muted mb-1">Menunggu</span>
                                @endif
                                <br>
                                <small><strong>Catatan:</strong> {{ $sempro->catatan_dospem2 ?? '-' }}</small>
                            </td>
                            <td>
                                @if($sempro && $sempro->laporan_sempro)
                                    <a href="{{ asset($sempro->laporan_sempro) }}" target="_blank" class="btn btn-info btn-sm me-1" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <form action="{{ route('mahasiswa.sempro.deleteLaporanTa') }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus Laporan TA?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                    </form>
                                @else
                                    <span class="text-muted">Belum ada file</span>
                                @endif
                            </td>
                        </tr>

                        {{-- 2. Form Persetujuan --}}
                        <tr>
                            <td>2</td>
                            <td>Form Persetujuan</td>
                            <td>
                                @if($sempro && $sempro->status_laporan_ta_dospem1 == 'Disetujui' && $sempro->status_laporan_ta_dospem2 == 'Disetujui')
                                    <form action="{{ route('mahasiswa.sempro.uploadForm') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="input-group input-group-sm">
                                            <input type="file" name="form_persetujuan_sempro" class="form-control" accept="application/pdf" required>
                                            <button type="submit" class="btn btn-primary" title="Upload">
                                                <i class="fas fa-upload"></i>
                                            </button>
                                        </div>
                                    </form>
                                @else
                                    <span class="text-muted">Menunggu ACC Laporan TA</span>
                                @endif
                            </td>
                            <td>
                                @if($sempro && $sempro->form_persetujuan_sempro)
                                    <span class="badge bg-success text-white">Sudah Upload</span>
                                @else
                                    <span class="text-muted">Belum Upload</span>
                                @endif
                            </td>
                            <td>
                                @if($sempro && $sempro->form_persetujuan_sempro)
                                    <a href="{{ asset($sempro->form_persetujuan_sempro) }}" target="_blank" class="btn btn-info btn-sm me-1" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <form action="{{ route('mahasiswa.sempro.deleteForm') }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus Form Persetujuan?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                    </form>
                                @else
                                    <span class="text-muted">Belum ada file</span>
                                @endif
                            </td>
                        </tr>

                        {{-- 3. Berita Acara --}}
                        <tr>
                            <td>3</td>
                            <td>Berita Acara</td>
                            <td>
                                @if($sempro && $sempro->form_persetujuan_sempro)
                                    <form action="{{ route('mahasiswa.sempro.uploadHasil') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="input-group input-group-sm">
                                            <input type="file" name="berita_acara_sempro" class="form-control" accept="application/pdf" required>
                                            <button type="submit" class="btn btn-primary" title="Upload">
                                                <i class="fas fa-upload"></i>
                                            </button>
                                        </div>
                                    </form>
                                @else
                                    <span class="text-muted">Menunggu Upload Form Persetujuan</span>
                                @endif
                            </td>
                            <td>
                                @if($sempro && $sempro->berita_acara_sempro)
                                    <span class="badge bg-success text-white">Sudah Upload</span>
                                @else
                                    <span class="text-muted">Belum Upload</span>
                                @endif
                            </td>
                            <td>
                                @if($sempro && $sempro->berita_acara_sempro)
                                    <a href="{{ asset($sempro->berita_acara_sempro) }}" target="_blank" class="btn btn-info btn-sm me-1" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <form action="{{ route('mahasiswa.sempro.deleteHasil') }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus Berita Acara?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                    </form>
                                @else
                                    <span class="text-muted">Belum ada file</span>
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
