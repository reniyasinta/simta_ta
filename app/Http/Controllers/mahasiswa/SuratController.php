<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Surat;
use Illuminate\Support\Facades\Storage;

class SuratController extends Controller
{
    public function index()
    {
        $mahasiswa = Mahasiswa::where('user_id', auth()->id())->first();
        $suratList = Surat::where('id_mhs', $mahasiswa->id_mhs)->latest()->get();

        return view('pages.mahasiswa.surat.index', compact('suratList'));
    }
    public function create()
    {
        return view('pages.mahasiswa.surat.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_ta' => 'required',
            'perihal' => 'required',
            'dosen_pembimbing' => 'required',
            'tujuan' => 'required',
        ]);

        $mahasiswa = Mahasiswa::where('user_id', auth()->id())->first();

        Surat::create([
            'id_mhs' => $mahasiswa->id_mhs,
            'judul_ta' => $request->judul_ta,
            'perihal' => $request->perihal,
            'dosen_pembimbing' => $request->dosen_pembimbing,
            'tujuan' => $request->tujuan,
            'status' => 'menunggu',
        ]);

        return redirect()->route('mahasiswa.surat.index')->with('success', 'Pengajuan surat berhasil.');
    }

    public function download($id)
    {
        $surat = Surat::findOrFail($id);

        if (!$surat->file_surat || !Storage::disk('public')->exists($surat->file_surat)) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($surat->file_surat);
    }
}
