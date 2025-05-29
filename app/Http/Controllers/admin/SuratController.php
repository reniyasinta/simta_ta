<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Surat;
use Illuminate\Support\Facades\Storage;


class SuratController extends Controller
{
public function index(Request $request)
    {
        $query = Surat::with(['mahasiswa.kelompok.anggota']);

        if ($request->filled('perihal')) {
            $query->where('perihal', $request->perihal);
        }

        $daftarSurat = $query->latest()->get();

        return view('pages.admin.surat.index', compact('daftarSurat'));
    }


    public function edit($id)
    {
        $surat = Surat::with('mahasiswa')->findOrFail($id);
        return view('pages.admin.surat.edit', compact('surat'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'file_surat' => 'required|mimes:pdf|max:20480',
        ]);

        $file = $request->file('file_surat');
        $originalName = $file->getClientOriginalName(); // nama asli file

        // Tambahkan timestamp agar tetap unik
        $filename = time() . '_' . $originalName;

        // Simpan file dengan nama asli + timestamp
        $path = $file->storeAs('surat_penelitian', $filename, 'public');

        $surat = Surat::findOrFail($id);
        $surat->file_surat = $path;

        $surat->status = 'selesai';
        $surat->save();

        return redirect()->route('admin.surat.index')->with('success', 'Surat berhasil diproses.');
    }


}
