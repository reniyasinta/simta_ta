<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Berkas;
use Illuminate\Support\Facades\Storage;

class BerkasController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $idProdi = $user->mahasiswa?->id_prodi;

        // Ambil berkas sesuai prodi mahasiswa
        $berkas = Berkas::when($idProdi, function ($query) use ($idProdi) {
            $query->where('id_prodi', $idProdi);
        })->get();


        return view('pages.mahasiswa.berkas.index', compact('berkas'));
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
