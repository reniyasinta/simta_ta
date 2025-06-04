<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanPembimbing;
use App\Models\Dosen;
use App\Models\User;


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
    $pengajuan = PengajuanPembimbing::with(['kelompok.anggota', 'dosen1.dosen', 'dosen2.dosen'])->findOrFail($id);

    // Ambil semua dosen role_id = 3 (tanpa group mapping)
    $dosenList = User::where('role_id', 3)->get();

    foreach ($dosenList as $dosen) {
        // Hitung total bimbingan sebagai DOSEN 2 saja!
        $jumlahSebagai2 = PengajuanPembimbing::where('id_dosen2', $dosen->id)
            ->where('status', 'Diterima')
            ->with('kelompok')
            ->get()
            ->sum(function ($pengajuan) {
                return $pengajuan->kelompok?->anggota->count() ?? 0;
            });

        // Cari kuota P2 dari tabel Dosen
        $dosenModel = \App\Models\Dosen::where('user_id', $dosen->id)->first();

        $kuotaP2 = null;
        if ($dosenModel) {
            $kuotaModel = \App\Models\KuotaBimbinganDosen::where('id_dosen', $dosenModel->id_dosen)->first();
            $kuotaP2 = $kuotaModel ? $kuotaModel->kuota_p2 : 0;
        }

        $dosen->kuota_total = $kuotaP2 ?? 0;
        $dosen->kuota_terpakai = $jumlahSebagai2;
    }

    return view('pages.panitia.pengajuan.edit', compact('pengajuan', 'dosenList'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'id_dosen2' => 'required|exists:users,id', // karena select value = User.id
    ]);

    $pengajuan = PengajuanPembimbing::findOrFail($id);

    // Simpan langsung user_id ke id_dosen2
    $pengajuan->id_dosen2 = $request->id_dosen2;
    $pengajuan->save();

    return redirect()->route('panitia.pengajuan.index')->with('success', 'Dosen Pembimbing 2 berhasil ditetapkan.');
}

}
