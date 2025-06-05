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
                        <th>Jenis</th>
                        <th>Preview</th>
                        <th>Upload</th>
                        <th>Status Dospem 1</th>
                        <th>Status Dospem 2</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>

                    {{-- Form Persetujuan --}}
                    <tr>
                        <td>1</td>
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
                            {{ $sempro->status_dospem1 ?? 'Belum diajukan' }}
                        </td>
                        <td>
                            {{ $sempro->status_dospem2 ?? 'Belum diajukan' }}
                        </td>
                        <td class="text-center">

                            @if($sempro && $sempro->form_persetujuan_sempro)

                                {{-- Button Hapus --}}
                                <form action="{{ route('mahasiswa.sempro.deleteForm') }}" method="POST" onsubmit="return confirm('Hapus Form Persetujuan?')" class="d-inline-block mb-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>

                                <div class="mt-1">
                                    @if($sempro->status_dospem1 == null && $sempro->status_dospem2 == null)
                                        {{-- Button Ajukan --}}
                                        <form action="{{ route('mahasiswa.sempro.ajukan') }}" method="POST" onsubmit="return confirm('Ajukan Form Persetujuan ke Dosen?')" class="d-inline-block">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="fas fa-paper-plane"></i> Ajukan ke Dosen
                                            </button>
                                        </form>
                                    @else
                                        {{-- Badge --}}
                                        <span class="badge bg-info" style="font-size: 0.9em;">Sudah Diajukan ke Dosen</span>
                                    @endif
                                </div>

                            @else
                                <span class="text-muted">Belum ada file</span>
                            @endif

                        </td>

                    </tr>

                    {{-- Hasil SEMPRO --}}
                    <tr>
                        <td>2</td>
                        <td>Hasil SEMPRO</td>
                        <td>
                            @if($sempro && $sempro->hasil_sempro)
                                <a href="{{ asset($sempro->hasil_sempro) }}" target="_blank" class="btn btn-sm btn-success">Lihat</a>
                            @else
                                <span class="text-muted">Belum ada</span>
                            @endif
                        </td>
                        <td>
                            @if($sempro && $sempro->status_dospem1 == 'Disetujui' && $sempro->status_dospem2 == 'Disetujui')
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
                            @else
                                <span class="text-muted">Menunggu Persetujuan Dosen</span>
                            @endif
                        </td>
                        <td colspan="3">
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
