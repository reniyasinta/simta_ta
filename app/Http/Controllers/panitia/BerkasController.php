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
            'nama_berkas' => 'required|string',
            'file' => 'required|mimes:pdf,ppt,pptx,xls,xlsx,doc,docx,txt,jpeg,jpg,png,rar,zip|max:20480',
        ]);

        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $originalName = $request->file('file')->getClientOriginalName();
            $filename = time() . '_' . $originalName;

            $path = $request->file('file')->storeAs('berkas', $filename, 'public');
        } else {
            return back()->withErrors(['file' => 'File tidak valid']);
        }

        Berkas::create([
            'nama_berkas' => $request->nama_berkas,
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
            'nama_berkas' => 'required|string',
            'file' => 'nullable|mimes:pdf,ppt,pptx,xls,xlsx,doc,docx,txt,jpeg,jpg,png,rar,zip|max:20480',
        ]);

        $berkas = Berkas::findOrFail($id);

        $path = $berkas->file_path;

        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            // Hapus file lama
            Storage::disk('public')->delete($berkas->file_path);

            // Simpan file baru dengan nama asli
            $originalName = $request->file('file')->getClientOriginalName();
            $filename = time() . '_' . $originalName;

            $path = $request->file('file')->storeAs('berkas', $filename, 'public');
        }

        $berkas->update([
            'nama_berkas' => $request->nama_berkas,
            'file_path' => $path,
        ]);

        return redirect()->route('pages.panitia.berkas.index')->with('success', 'Berkas berhasil diperbarui');
    }

    public function destroy($id)
    {
        $berkas = Berkas::findOrFail($id);

        Storage::disk('public')->delete($berkas->file_path);

        $berkas->delete();

        return redirect()->route('pages.panitia.berkas.index')->with('success', 'Berkas berhasil dihapus');
    }

    public function download($id)
    {
        $berkas = Berkas::findOrFail($id);

        if (Storage::disk('public')->exists($berkas->file_path)) {
            $extension = pathinfo($berkas->file_path, PATHINFO_EXTENSION);
            return Storage::disk('public')->download($berkas->file_path, $berkas->nama_berkas . '.' . $extension);
        }

        return redirect()->back()->with('error', 'File tidak ditemukan.');
    }
}
