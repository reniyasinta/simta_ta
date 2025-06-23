@extends('layouts.app')

@section('title', 'Daftar Berkas Persyaratan')

@push('style')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endpush

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Daftar Berkas Persyaratan</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('home') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Berkas</div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Tabel Berkas</h4>
                <a href="{{ route('panitia.berkas.create') }}" class="btn btn-success btn-sm">+ Upload Berkas</a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="table-berkas" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Berkas</th>
                                <th>Preview</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($berkas as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @php
                                            $extension = pathinfo($item->file_path, PATHINFO_EXTENSION);
                                            $icons = [
                                                'pdf' => 'fas fa-file-pdf text-danger',
                                                'doc' => 'fas fa-file-word text-primary',
                                                'docx' => 'fas fa-file-word text-primary',
                                                'xls' => 'fas fa-file-excel text-success',
                                                'xlsx' => 'fas fa-file-excel text-success',
                                                'ppt' => 'fas fa-file-powerpoint text-warning',
                                                'pptx' => 'fas fa-file-powerpoint text-warning',
                                                'jpg' => 'fas fa-file-image text-info',
                                                'jpeg' => 'fas fa-file-image text-info',
                                                'png' => 'fas fa-file-image text-info',
                                                'txt' => 'fas fa-file-alt text-secondary',
                                                'zip' => 'fas fa-file-archive text-muted',
                                                'rar' => 'fas fa-file-archive text-muted',
                                            ];
                                            $icon = $icons[strtolower($extension)] ?? 'fas fa-file';
                                            $canPreviewDirect = in_array(strtolower($extension), ['pdf', 'jpg', 'jpeg', 'png', 'txt']);
                                            $canGoogleViewer = in_array(strtolower($extension), ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx']);
                                            $fileUrl = Storage::url($item->file_path);
                                            $googleViewerUrl = 'https://docs.google.com/gview?url=' . urlencode(asset('storage/' . $item->file_path)) . '&embedded=true';
                                            $viewerLink = $canPreviewDirect ? $fileUrl : ($canGoogleViewer ? $googleViewerUrl : $fileUrl);
                                        @endphp
                                        <i class="{{ $icon }}"></i> {{ $item->nama_berkas }}
                                    </td>
                                    <td>
                                        @if(Storage::disk('public')->exists($item->file_path))
                                            <a href="{{ $viewerLink }}" target="_blank" class="btn btn-info btn-sm">Lihat</a>
                                        @else
                                            <span class="text-danger">File tidak ditemukan</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('panitia.berkas.edit', $item->id_berkas) }}"
                                           class="btn btn-warning btn-sm me-1" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <form action="{{ route('panitia.berkas.destroy', $item->id_berkas) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Yakin ingin menghapus berkas ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Belum ada berkas diunggah.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('#table-berkas').DataTable({
            language: {
                search: "Cari Nama Berkas:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                zeroRecords: "Data tidak ditemukan",
                info: "Menampilkan _PAGE_ dari _PAGES_",
                infoEmpty: "Tidak ada data",
                infoFiltered: "(difilter dari _MAX_ total data)"
            },
            pageLength: 10,
            autoWidth: false
        });
    });
</script>
@endpush
