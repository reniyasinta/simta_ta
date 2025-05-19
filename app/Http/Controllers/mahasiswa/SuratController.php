<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Surat;

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
            'dosen_pembimbing' => 'required',
            'tujuan' => 'required',
        ]);

        $mahasiswa = Mahasiswa::where('user_id', auth()->id())->first();

        Surat::create([
            'id_mhs' => $mahasiswa->id_mhs,
            'judul_ta' => $request->judul_ta,
            'dosen_pembimbing' => $request->dosen_pembimbing,
            'tujuan' => $request->tujuan,
            'status' => 'menunggu',
        ]);

        return redirect()->route('mahasiswa.surat.index')->with('success', 'Pengajuan surat berhasil.');
    }
}
