<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sidang;
use Illuminate\Support\Facades\Storage;

class SidangController extends Controller
{
    public function index()
    {
        $sidangs = Sidang::with('mahasiswa')->get();
        return view('pages.panitia.sidang.index', compact('sidangs'));
    }

    public function upload(Request $request, $id)
    {
        $request->validate([
            'laporan_akhir' => 'nullable|file|mimes:pdf|max:2048',
            'lembar_konsultasi' => 'nullable|file|mimes:pdf|max:2048',
            'hasil_sidang' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $sidang = Sidang::findOrFail($id);

        foreach (['laporan_akhir', 'lembar_konsultasi', 'hasil_sidang'] as $field) {
            if ($request->hasFile($field)) {
                $filename = $field . '_' . time() . '.' . $request->file($field)->getClientOriginalExtension();
                $path = $request->file($field)->storeAs('public/sidang', $filename);
                $sidang->$field = 'storage/sidang/' . $filename;
            }
        }

        $sidang->save();

        return back()->with('success', 'Berkas berhasil diunggah.');
    }
}
