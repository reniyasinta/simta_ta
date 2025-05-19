<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanPembimbing;

class ValidasiPengajuanController extends Controller
{
    public function index()
    {
        // Ambil user_id dosen yang login
        $userId = auth()->id();

        $pengajuan = PengajuanPembimbing::with([
            'kelompok.anggota1.mahasiswa',
            'kelompok.anggota2.mahasiswa',
            'kelompok.anggota3.mahasiswa'
        ])
        ->where('id_dosen1', $userId)
        ->get();

        return view('pages.dosen.validasi.index', compact('pengajuan'));
    }


    public function validasi(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Diterima,Ditolak',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $pengajuan = PengajuanPembimbing::findOrFail($id);
        $pengajuan->status = $request->status;
        $pengajuan->keterangan = $request->keterangan;
        $pengajuan->save();

        return redirect()->route('dosen.validasi')->with('success', 'Pengajuan berhasil divalidasi.');
    }
}

