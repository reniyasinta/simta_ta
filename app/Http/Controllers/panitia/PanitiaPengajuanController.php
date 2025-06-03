<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanPembimbing;
use App\Models\Dosen;

class PanitiaPengajuanController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Ambil pengajuan mahasiswa sesuai prodi panitia
        $pengajuanList = PengajuanPembimbing::with(['dosen1', 'dosen2', 'kelompok.anggota1.mahasiswa'])
            ->where('status', 'Diterima')
            ->whereHas('kelompok.anggota1.mahasiswa', function ($q) use ($user) {
                if ($user->role->name === 'panitia' && $user->id_prodi !== null) {
                    // Filter mahasiswa sesuai id_prodi panitia
                    $q->where('id_prodi', $user->id_prodi);
                }
                // Kalau panitia jurusan (id_prodi null) → tidak ada filter, tampilkan semua
            })
            ->get();

        // Dosen sementara → ambil semua dulu (nanti bisa pakai group mapping)
        $dosenList = Dosen::all();

        return view('pages.panitia.pengajuan.index', compact('pengajuanList', 'dosenList'));
    }


    public function edit($id)
    {
        $user = auth()->user();

        $pengajuan = PengajuanPembimbing::with(['kelompok', 'dosen1', 'dosen2'])
            ->findOrFail($id);

        // Dosen sementara → ambil semua dulu
        $dosenList = Dosen::all();

        return view('pages.panitia.pengajuan.edit', compact('pengajuan', 'dosenList'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_dosen2' => 'required|exists:dosen,id_dosen', // tetap validasi dari dosen
        ]);

        // Ambil ID user dari tabel dosen
        $dosen = Dosen::findOrFail($request->id_dosen2);
        $pengajuan = PengajuanPembimbing::findOrFail($id);

        // Simpan ke id_dosen2 → simpan user_id dosen (seperti di create)
        $pengajuan->id_dosen2 = $dosen->user_id;
        $pengajuan->save();

        return redirect()->route('panitia.pengajuan.index')->with('success', 'Dosen Pembimbing 2 berhasil ditetapkan.');
    }
}
