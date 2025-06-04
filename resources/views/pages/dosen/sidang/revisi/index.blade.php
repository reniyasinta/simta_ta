@extends('layouts.app')

@section('title', 'ACC Revisi Sidang')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>ACC Revisi Sidang</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Mahasiswa</th>
                    <th>Revisi Laporan</th>
                    <th>Status Revisi</th>
                    <th>Catatan Dosen</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sidangList as $index => $sidang)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $sidang->mahasiswa->nama_mhs ?? '-' }}</td>
                    <td>
                        @if($sidang->revisi_laporan)
                            <a href="{{ asset($sidang->revisi_laporan) }}" target="_blank" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                        @else
                            <span class="text-muted">Belum Upload</span>
                        @endif
                    </td>
                    <td>{{ $sidang->status_revisi }}</td>
                    <td>{{ $sidang->catatan_revisi ?? '-' }}</td>
                    <td>
                        <form action="{{ route('dosen.sidang.updateStatusRevisi', $sidang->id_sidang) }}" method="POST" style="display: inline-block;">
                            @csrf
                            <select name="status_revisi" class="form-control form-control-sm d-inline w-auto mb-2" required>
                                <option value="Menunggu" {{ $sidang->status_revisi == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="Revisi" {{ $sidang->status_revisi == 'Revisi' ? 'selected' : '' }}>Revisi</option>
                                <option value="Disetujui" {{ $sidang->status_revisi == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                            </select>
                            <textarea name="catatan_revisi" class="form-control form-control-sm mb-2" placeholder="Catatan Dosen">{{ $sidang->catatan_revisi }}</textarea>
                            <button type="submit" class="btn btn-sm btn-primary">Update</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </section>
</div>
@endsection
