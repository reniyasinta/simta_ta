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
        $user = auth()->user();
        $idProdiPanitia = $user->id_prodi;

        // Panitia jurusan: lihat semua, Panitia prodi: lihat sesuai prodi
        $berkas = Berkas::with('prodi')
            ->when($idProdiPanitia !== null, function ($query) use ($idProdiPanitia) {
                $query->where('id_prodi', $idProdiPanitia);
            })
            ->get();

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

    $user = auth()->user();

    // ⛔ Jika panitia jurusan (tidak punya prodi), tolak
    if ($user->id_prodi === null) {
        return back()->with('error', 'Akun Anda tidak memiliki prodi terkait.');
    }

    // ✅ Simpan file
    if ($request->hasFile('file') && $request->file('file')->isValid()) {
        $originalName = $request->file('file')->getClientOriginalName();
        $filename = time() . '_' . $originalName;
        $path = $request->file('file')->storeAs('berkas', $filename, 'public');
    } else {
        return back()->withErrors(['file' => 'File tidak valid']);
    }

    // ✅ Simpan ke DB dengan id_prodi
    Berkas::create([
        'nama_berkas' => $request->nama_berkas,
        'file_path' => $path,
        'id_prodi' => $user->id_prodi,
    ]);

    return redirect()->route('panitia.berkas.index')->with('success', 'Berkas berhasil diupload');
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

            $originalName = $request->file('file')->getClientOriginalName();
            $filename = time() . '_' . $originalName;
            $path = $request->file('file')->storeAs('berkas', $filename, 'public');
        }

        $berkas->update([
            'nama_berkas' => $request->nama_berkas,
            'file_path' => $path,
        ]);

        return redirect()->route('panitia.berkas.index')->with('success', 'Berkas berhasil diperbarui');
    }

    public function destroy($id)
    {
        $berkas = Berkas::findOrFail($id);
        Storage::disk('public')->delete($berkas->file_path);
        $berkas->delete();

        return redirect()->route('panitia.berkas.index')->with('success', 'Berkas berhasil dihapus');
    }

    public function download($id)
    {
        $berkas = Berkas::findOrFail($id);

        if (Storage::disk('public')->exists($berkas->file_path)) {
            $ext = pathinfo($berkas->file_path, PATHINFO_EXTENSION);
            return Storage::disk('public')->download($berkas->file_path, $berkas->nama_berkas . '.' . $ext);
        }

        return redirect()->back()->with('error', 'File tidak ditemukan.');
    }
}
