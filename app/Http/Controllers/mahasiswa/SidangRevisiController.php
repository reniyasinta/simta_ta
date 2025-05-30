<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sidang;
use Illuminate\Support\Facades\Storage;

class SidangRevisiController extends Controller
{
    public function index()
    {
        $sidang = Sidang::where('id_mhs', Auth::user()->mahasiswa->id_mhs)->first();

        return view('pages.mahasiswa.revisi.index', compact('sidang'));
    }

   public function create()
    {
        return view('pages.mahasiswa.revisi.create');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'revisi_laporan' => 'required|mimes:pdf|max:20480',
        ]);

        $sidang = Sidang::where('id_mhs', Auth::user()->mahasiswa->id_mhs)->first();

        if (!$sidang) {
            return redirect()->back()->with('error', 'Data sidang tidak ditemukan.');
        }

        if ($request->hasFile('revisi_laporan')) {
            // Hapus file lama
            if ($sidang->revisi_laporan && Storage::exists(str_replace('storage/', '', $sidang->revisi_laporan))) {
                Storage::delete(str_replace('storage/', '', $sidang->revisi_laporan));
            }

            $path = $request->file('revisi_laporan')->store('uploads/revisi_laporan', 'public');
            $sidang->revisi_laporan = 'storage/' . $path;
            $sidang->status = 'Menunggu';
            $sidang->catatan_dosen = null; // Reset catatan
            $sidang->save();
        }

        return redirect()->route('mahasiswa.revisi.index')->with('success', 'Revisi laporan berhasil diupload.');
    }
}

