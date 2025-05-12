@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Daftar Berkas Persyaratan</h4>
    
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Berkas</th>
                <th>Kategori</th>
                <th>Preview</th>
                <th>Unduh</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($berkas as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->nama_berkas }}</td>
                    <td>{{ $item->kategori }}</td>
                    <td>
                        <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" class="btn btn-info btn-sm">Lihat</a>
                    </td>
                    <td>
                        <a href="{{ route('mahasiswa.berkas.download', $item->id) }}" class="btn btn-success btn-sm">Download</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
