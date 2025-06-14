@extends('layouts.app')

@section('title', 'Daftar Berkas Persyaratan')

@push('style')
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

        <div class="table-responsive">
            <table id="table-berkas" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Berkas</th>
                        <th>Preview</th>
                        <th>Download</th>
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

                                    $canPreview = in_array(strtolower($extension), ['pdf', 'jpg', 'jpeg', 'png', 'txt']);
                                    $canGoogleViewer = in_array(strtolower($extension), ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx']);
                                    $fileUrl = Storage::url($item->file_path);
                                    $googleViewerUrl = 'https://docs.google.com/gview?url=' . urlencode(asset('storage/' . $item->file_path)) . '&embedded=true';
                                @endphp
                                <i class="{{ $icon }}"></i> {{ $item->nama_berkas }}
                            </td>
                            <td>
                                @if(Storage::disk('public')->exists($item->file_path))
                                    @php
                                        $extension = pathinfo($item->file_path, PATHINFO_EXTENSION);
                                        $canPreviewDirect = in_array(strtolower($extension), ['pdf', 'jpg', 'jpeg', 'png', 'txt']);
                                        $canGoogleViewer = in_array(strtolower($extension), ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx']);
                                        $fileUrl = Storage::url($item->file_path);
                                        $googleViewerUrl = 'https://docs.google.com/gview?url=' . urlencode(asset('storage/' . $item->file_path)) . '&embedded=true';
                                    @endphp

                                    <a href="{{ $canPreviewDirect ? $fileUrl : ($canGoogleViewer ? $googleViewerUrl : $fileUrl) }}" target="_blank" class="btn btn-info btn-sm">
                                        Lihat
                                    </a>
                                @else
                                    <span class="text-danger">File tidak ditemukan</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('mahasiswa.berkas.download', $item->id_berkas) }}" class="btn btn-success btn-sm">Download</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada berkas tersedia untuk prodi Anda.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#table-berkas').DataTable({
            language: {
                search: "Cari Nama Berkas:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                zeroRecords: "Data tidak ditemukan",
                info: "Menampilkan _PAGE_ dari _PAGES_",
                infoEmpty: "Tidak ada data",
                infoFiltered: "(filtered from _MAX_ total records)"
            },
            pageLength: 10
        });
    });
</script>
@endpush
