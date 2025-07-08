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

        {{-- PERINGATAN --}}
        <div class="alert alert-warning">
            <strong>Perhatian!</strong> Untuk dapat mengikuti Seminar Proposal (Sempro), mahasiswa <strong>wajib mengunggah Proposal Tugas Akhir</strong> dan <strong>Form Persetujuan Sempro</strong>.
            <br>
            <ul class="mb-1 mt-1">
                <li>Unggah <strong>Proposal</strong> terlebih dahulu dan tunggu persetujuan dari Dosen Pembimbing 1 & 2.</li>
                <li>Setelah disetujui, unggah <strong>Form Persetujuan</strong>.</li>
                <li>Panitia akan menjadwalkan tanggal sempro hanya jika <strong>Form Persetujuan sudah diunggah</strong>.</li>
                <li><strong>Berita Acara</strong> diunggah setelah seminar selesai.</li>
            </ul>
        </div>

        @php $editFile = request()->get('edit'); @endphp

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

                        {{-- 1. Proposal Tugas Akhir --}}
                        <tr>
                            <td>1</td>
                            <td>Proposal Tugas Akhir</td>
                            <td>
                                @if($sempro && $sempro->proposal_ta && $editFile !== 'proposal')
                                    <span class="badge bg-success text-white">Sudah Upload</span>
                                @else
                                    <form action="{{ route('mahasiswa.sempro.uploadLaporanTa') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="input-group input-group-sm">
                                            <input type="file" name="proposal_ta" class="form-control" accept="application/pdf" required>
                                            <button type="submit" class="btn btn-primary" title="Upload">
                                                <i class="fas fa-upload"></i>
                                            </button>
                                        </div>
                                    </form>
                                @endif
                            </td>
                            <td>
                                Dospem 1:
                                @if($sempro && $sempro->status_proposal_ta_dospem1 == 'Disetujui')
                                    <span class="badge bg-success text-white mb-1">Acc</span>
                                @elseif($sempro && $sempro->status_proposal_ta_dospem1 == 'Revisi')
                                    <span class="badge bg-warning text-dark mb-1">Revisi</span>
                                @else
                                    <span class="text-muted mb-1">Menunggu</span>
                                @endif
                                <br>
                                <small><strong>Catatan:</strong> {{ $sempro->catatan_dospem1 ?? '-' }}</small>
                                <br>Dospem 2:
                                @if($sempro && $sempro->status_proposal_ta_dospem2 == 'Disetujui')
                                    <span class="badge bg-success text-white mb-1">Acc</span>
                                @elseif($sempro && $sempro->status_proposal_ta_dospem2 == 'Revisi')
                                    <span class="badge bg-warning text-dark mb-1">Revisi</span>
                                @else
                                    <span class="text-muted mb-1">Menunggu</span>
                                @endif
                                <br>
                                <small><strong>Catatan:</strong> {{ $sempro->catatan_dospem2 ?? '-' }}</small>
                            </td>
                            <td>
                                @if($sempro && $sempro->proposal_ta)
                                    <a href="{{ asset($sempro->proposal_ta) }}" target="_blank" class="btn btn-info btn-sm me-1" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('mahasiswa.sempro.index', ['edit' => 'proposal']) }}" class="btn btn-warning btn-sm me-1" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
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
                                @if($sempro && $sempro->form_persetujuan_sempro && $editFile !== 'form')
                                    <span class="badge bg-success text-white">Sudah Upload</span>
                                @elseif($sempro && $sempro->status_proposal_ta_dospem1 == 'Disetujui' && $sempro->status_proposal_ta_dospem2 == 'Disetujui')
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
                                    <a href="{{ route('mahasiswa.sempro.index', ['edit' => 'form']) }}" class="btn btn-warning btn-sm me-1" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
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
                                @if($sempro && $sempro->berita_acara_sempro && $editFile !== 'berita')
                                    <span class="badge bg-success text-white">Sudah Upload</span>
                                @elseif($sempro && $sempro->form_persetujuan_sempro)
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
                                    <a href="{{ route('mahasiswa.sempro.index', ['edit' => 'berita']) }}" class="btn btn-warning btn-sm me-1" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
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
