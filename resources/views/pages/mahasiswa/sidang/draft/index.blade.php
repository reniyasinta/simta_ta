@extends('layouts.app')

@section('title', 'Laporan TA Draft')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Laporan Tugas Akhir</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @php $editFile = request()->get('edit'); @endphp

        <div class="card">
            <div class="card-header">
                <h4>Daftar Berkas Tugas Akhir</h4>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis</th>
                            <th>Upload File (PDF)</th>
                            <th>Status & Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                        {{-- 1. Laporan TA Draft --}}
                        <tr>
                            <td>1</td>
                            <td>Laporan Tugas Akhir</td>
                            <td>
                                @if($editFile === 'laporan_TA')
                                    <form action="{{ route('mahasiswa.sidang.uploadDraft') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="input-group input-group-sm">
                                            <input type="file" name="laporan_TA" class="form-control" accept="application/pdf" required>
                                            <button type="submit" class="btn btn-primary" title="Upload"><i class="fas fa-upload"></i></button>
                                        </div>
                                    </form>
                                @elseif($sidang && $sidang->laporan_TA)
                                    <span class="badge bg-success text-white">Sudah Upload</span>
                                @else
                                    <form action="{{ route('mahasiswa.sidang.uploadDraft') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="input-group input-group-sm">
                                            <input type="file" name="laporan_TA" class="form-control" accept="application/pdf" required>
                                            <button type="submit" class="btn btn-primary" title="Upload"><i class="fas fa-upload"></i></button>
                                        </div>
                                    </form>
                                @endif
                            </td>
                            <td>
                                Dospem 1:
                                @if($sidang && $sidang->status_draft_dosen1 == 'Disetujui')
                                    <span class="badge bg-success text-white mb-1">Acc</span>
                                @elseif($sidang && $sidang->status_draft_dosen1 == 'Revisi')
                                    <span class="badge bg-warning text-dark mb-1">Revisi</span>
                                @else
                                    <span class="text-muted mb-1">Menunggu</span>
                                @endif
                                <br>
                                <small><strong>Catatan:</strong> {{ $sidang->catatan_draft_dosen1 ?? '-' }}</small>

                                <br>Dospem 2:
                                @if($sidang && $sidang->status_draft_dosen2 == 'Disetujui')
                                    <span class="badge bg-success text-white mb-1">Acc</span>
                                @elseif($sidang && $sidang->status_draft_dosen2 == 'Revisi')
                                    <span class="badge bg-warning text-dark mb-1">Revisi</span>
                                @else
                                    <span class="text-muted mb-1">Menunggu</span>
                                @endif
                                <br>
                                <small><strong>Catatan:</strong> {{ $sidang->catatan_draft_dosen2 ?? '-' }}</small>
                            </td>
                            <td>
                                @if($sidang && $sidang->laporan_TA)
                                    <a href="{{ asset($sidang->laporan_TA) }}" target="_blank" class="btn btn-info btn-sm me-1" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('mahasiswa.sidang.draft', ['edit' => 'laporan_TA']) }}" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                @else
                                    <span class="text-muted">Belum ada file</span>
                                @endif
                            </td>
                        </tr>

                        {{-- 2. Form Persetujuan Sidang --}}
                        <tr>
                            <td>2</td>
                            <td>Form Persetujuan Sidang</td>
                            <td>
                                @if($editFile === 'from_persetujuan_sidang')
                                    <form action="{{ route('mahasiswa.sidang.uploadDraft') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="input-group input-group-sm">
                                            <input type="file" name="from_persetujuan_sidang" class="form-control" accept="application/pdf" required>
                                            <button type="submit" class="btn btn-primary" title="Upload"><i class="fas fa-upload"></i></button>
                                        </div>
                                    </form>
                                @elseif($sidang && $sidang->from_persetujuan_sidang)
                                    <span class="badge bg-success text-white">Sudah Upload</span>
                                @elseif($sidang && $sidang->status_draft_dosen1 == 'Disetujui' && $sidang->status_draft_dosen2 == 'Disetujui')
                                    <form action="{{ route('mahasiswa.sidang.uploadDraft') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="input-group input-group-sm">
                                            <input type="file" name="from_persetujuan_sidang" class="form-control" accept="application/pdf" required>
                                            <button type="submit" class="btn btn-primary" title="Upload"><i class="fas fa-upload"></i></button>
                                        </div>
                                    </form>
                                @else
                                    <span class="text-muted">Menunggu ACC Laporan Draft</span>
                                @endif
                            </td>
                            <td>
                                @if($sidang && $sidang->from_persetujuan_sidang)
                                    <span class="badge bg-success text-white">Sudah Upload</span>
                                @else
                                    <span class="text-muted">Belum Upload</span>
                                @endif
                            </td>
                            <td>
                                @if($sidang && $sidang->from_persetujuan_sidang)
                                    <a href="{{ asset($sidang->from_persetujuan_sidang) }}" target="_blank" class="btn btn-info btn-sm me-1" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('mahasiswa.sidang.draft', ['edit' => 'from_persetujuan_sidang']) }}" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                @else
                                    <span class="text-muted">Belum ada file</span>
                                @endif
                            </td>
                        </tr>

                        {{-- 3. Lembar Konsultasi --}}
                        <tr>
                            <td>3</td>
                            <td>Lembar Konsultasi</td>
                            <td>
                                @if($editFile === 'lembar_konsultasi')
                                    <form action="{{ route('mahasiswa.sidang.uploadDraft') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="input-group input-group-sm">
                                            <input type="file" name="lembar_konsultasi" class="form-control" accept="application/pdf" required>
                                            <button type="submit" class="btn btn-primary" title="Upload"><i class="fas fa-upload"></i></button>
                                        </div>
                                    </form>
                                @elseif($sidang && $sidang->lembar_konsultasi)
                                    <span class="badge bg-success text-white">Sudah Upload</span>
                                @elseif($sidang && $sidang->from_persetujuan_sidang)
                                    <form action="{{ route('mahasiswa.sidang.uploadDraft') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="input-group input-group-sm">
                                            <input type="file" name="lembar_konsultasi" class="form-control" accept="application/pdf" required>
                                            <button type="submit" class="btn btn-primary" title="Upload"><i class="fas fa-upload"></i></button>
                                        </div>
                                    </form>
                                @else
                                    <span class="text-muted">Menunggu Upload Form Persetujuan</span>
                                @endif
                            </td>
                            <td>
                                @if($sidang && $sidang->lembar_konsultasi)
                                    <span class="badge bg-success text-white">Sudah Upload</span>
                                @else
                                    <span class="text-muted">Belum Upload</span>
                                @endif
                            </td>
                            <td>
                                @if($sidang && $sidang->lembar_konsultasi)
                                    <a href="{{ asset($sidang->lembar_konsultasi) }}" target="_blank" class="btn btn-info btn-sm me-1" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('mahasiswa.sidang.draft', ['edit' => 'lembar_konsultasi']) }}" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
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
