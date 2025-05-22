<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\User;

class DosenKuotaController extends Controller
{
    public function index()
    {
        // Ambil semua dosen dengan relasi user & prodi
        $dosenList = Dosen::with('user', 'prodi')->get();

        // Hitung jumlah bimbingan masing-masing dosen
        foreach ($dosenList as $dosen) {
            $jumlahSebagai1 = \App\Models\PengajuanPembimbing::where('id_dosen1', $dosen->user_id)
                ->where('status', 'Diterima')
                ->count();

            $jumlahSebagai2 = \App\Models\PengajuanPembimbing::where('id_dosen2', $dosen->user_id)
                ->where('status', 'Diterima')
                ->count();

            $dosen->bimbingan_terpakai = $jumlahSebagai1 + $jumlahSebagai2;
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
        $dosen->save();

        return redirect()->route('panitia.kuota.index')->with('success', 'Kuota berhasil diperbarui.');
    }
}
