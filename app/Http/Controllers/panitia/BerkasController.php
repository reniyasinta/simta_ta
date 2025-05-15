<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Berkas;
use Illuminate\Support\Facades\Storage;

class BerkasController extends Controller
{
    public function index()
    {
        $berkas = Berkas::all();
        return view('pages.panitia.berkas.index', compact('berkas'));
    }

    public function create()
    {
        return view('pages.panitia.berkas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|in:sempro,ta,buku_pedoman',
            'nama_berkas' => 'required|string',
            'file' => 'required|mimes:pdf|max:20480',
        ]);

        // Validasi file upload
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $path = $request->file('file')->store('berkas', 'public');
        } else {
            return back()->withErrors(['file' => 'File tidak valid']);
}
        Berkas::create([
            'nama_berkas' => $request->nama_berkas,
            'kategori' => $request->kategori,
            'file_path' => $path,
        ]);

        return redirect()->route('pages.panitia.berkas.index')->with('success', 'Berkas berhasil diunggah');
    }

    public function edit($id)
    {
        $berkas = Berkas::findOrFail($id);
        return view('pages.panitia.berkas.edit', compact('berkas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori' => 'required|in:sempro,ta,buku_pedoman',
            'nama_berkas' => 'required|string',
            'file' => 'nullable|mimes:pdf|max:20480',
        ]);

        $berkas = Berkas::findOrFail($id);

        $path = $berkas->file_path;

        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            // Hapus file lama
            Storage::disk('public')->delete($berkas->file_path);

            // Simpan file baru
            $path = $request->file('file')->store('berkas', 'public');
        }

        $berkas->update([
            'nama_berkas' => $request->nama_berkas,
            'kategori' => $request->kategori,
            'file_path' => $path,
        ]);

        return redirect()->route('pages.panitia.berkas.index')->with('success', 'Berkas berhasil diperbarui');
    }

    public function destroy($id)
    {
        $berkas = Berkas::findOrFail($id);

        // Hapus file fisik
        Storage::disk('public')->delete($berkas->file_path);

        $berkas->delete();

        return redirect()->route('pages.panitia.berkas.index')->with('success', 'Berkas berhasil dihapus');
    }

        public function download($id)
        {
            $berkas = Berkas::findOrFail($id);
            return Storage::disk('public')->download($berkas->file_path, $berkas->nama_berkas . '.pdf');
        }
}
