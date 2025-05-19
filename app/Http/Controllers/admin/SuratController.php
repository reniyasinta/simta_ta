<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Surat;

class SuratController extends Controller
{
    public function index()
    {
        $daftarSurat = Surat::with('mahasiswa')->get();
        return view('pages.admin.surat.index', compact('daftarSurat'));
    }

    public function edit($id)
    {
        $surat = Surat::with('mahasiswa')->findOrFail($id);
        return view('admin.surat.edit', compact('surat'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'file_surat' => 'required|mimes:pdf|max:2048',
        ]);

        $path = $request->file('file_surat')->store('surat_penelitian', 'public');

        $surat = Surat::findOrFail($id);
        $surat->file_surat = $path;
        $surat->status = 'selesai';
        $surat->save();

        return redirect()->route('admin.surat.index')->with('success', 'Surat berhasil diproses.');
    }
}
