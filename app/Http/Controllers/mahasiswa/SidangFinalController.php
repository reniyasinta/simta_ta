<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sidang;
use Illuminate\Support\Facades\Storage;


class SidangFinalController extends Controller
{
    public function index()
    {
        $sidang = Sidang::where('id_mhs', Auth::user()->mahasiswa->id_mhs)->first();

        return view('pages.mahasiswa.final.index', compact('sidang'));
    }

    public function create()
    {
        return view('pages.mahasiswa.final.create');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'laporan_akhir' => 'required|mimes:pdf|max:2048',
            'lembar_konsultasi' => 'required|mimes:pdf|max:2048',
            'hasil_sidang' => 'required|mimes:pdf|max:2048',
        ]);


        $sidang = Sidang::where('id_mhs', Auth::user()->mahasiswa->id_mhs)->first();

        if (!$sidang) {
            return redirect()->back()->with('error', 'Data sidang tidak ditemukan.');
        }

        // Upload masing-masing file
        if ($request->hasFile('laporan_akhir')) {
            if ($sidang->laporan_akhir && Storage::exists(str_replace('storage/', '', $sidang->laporan_akhir))) {
                Storage::delete(str_replace('storage/', '', $sidang->laporan_akhir));
            }
            $path = $request->file('laporan_akhir')->store('uploads/laporan_akhir', 'public');
            $sidang->laporan_akhir = 'storage/' . $path;
        }

        if ($request->hasFile('lembar_konsultasi')) {
            if ($sidang->lembar_konsultasi && Storage::exists(str_replace('storage/', '', $sidang->lembar_konsultasi))) {
                Storage::delete(str_replace('storage/', '', $sidang->lembar_konsultasi));
            }
            $path = $request->file('lembar_konsultasi')->store('uploads/lembar_konsultasi', 'public');
            $sidang->lembar_konsultasi = 'storage/' . $path;
        }

        if ($request->hasFile('hasil_sidang')) {
            if ($sidang->hasil_sidang && Storage::exists(str_replace('storage/', '', $sidang->hasil_sidang))) {
                Storage::delete(str_replace('storage/', '', $sidang->hasil_sidang));
            }
            $path = $request->file('hasil_sidang')->store('uploads/hasil_sidang', 'public');
            $sidang->hasil_sidang = 'storage/' . $path;
        }

        $sidang->save();

        return redirect()->route('mahasiswa.final.index')->with('success', 'Berkas final berhasil diupload.');
    }
}
