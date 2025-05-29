<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\User;
use App\Models\Kelompok;

class DosenKuotaController extends Controller
{
    public function index()
    {
        // Ambil semua dosen dengan relasi user & prodi
        $dosenList = Dosen::with('user', 'prodi')->get();

        // Hitung jumlah bimbingan masing-masing dosen
foreach ($dosenList as $dosen) {
    $jumlah = 0;

    // Ambil semua pengajuan di mana dosen sebagai pembimbing 1
    $pengajuan1 = \App\Models\PengajuanPembimbing::where('id_dosen1', $dosen->user_id)
        ->where('status', 'Diterima')
        ->with('kelompok')
        ->get();

    // Ambil semua pengajuan di mana dosen sebagai pembimbing 2
    $pengajuan2 = \App\Models\PengajuanPembimbing::where('id_dosen2', $dosen->user_id)
        ->where('status', 'Diterima')
        ->with('kelompok')
        ->get();

    // Hitung jumlah mahasiswa di setiap kelompok
    foreach ($pengajuan1 as $pengajuan) {
        $jumlah += $pengajuan->kelompok?->jumlah_anggota ?? 0;
    }

    foreach ($pengajuan2 as $pengajuan) {
        $jumlah += $pengajuan->kelompok?->jumlah_anggota ?? 0;
    }

    $dosen->bimbingan_terpakai = $jumlah;
}


        return view('pages.panitia.kuota.index', compact('dosenList'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'kuota_bimbingan' => 'required|integer|min:0',
        ]);

        $dosen = Dosen::findOrFail($id);
        $dosen->kuota_bimbingan = $request->kuota_bimbingan;
        $dosen->kuota_p2 = $request->kuota_p2;
        $dosen->save();

        return redirect()->route('panitia.kuota.index')->with('success', 'Kuota berhasil diperbarui.');
    }
}
