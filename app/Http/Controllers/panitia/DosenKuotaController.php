<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\User;
use App\Models\Kelompok;
use App\Models\KuotaBimbinganDosen;

class DosenKuotaController extends Controller
{
    public function index()
{
    $user = auth()->user();
    $idProdiPanitia = $user->id_prodi;

    $dosenList = Dosen::with('user', 'prodi')->get();

    foreach ($dosenList as $dosen) {
        $jumlah = 0;

        // Dosen sebagai Pembimbing 1 → dihitung hanya untuk mahasiswa prodi panitia
        $pengajuan1 = \App\Models\PengajuanPembimbing::where('id_dosen1', $dosen->user_id)
            ->where('status', 'Diterima')
            ->whereHas('kelompok.anggota', function ($query) use ($idProdiPanitia) {
                $query->where('id_prodi', $idProdiPanitia);
            })
            ->with('kelompok')
            ->get();

        // Dosen sebagai Pembimbing 2 → dihitung hanya untuk mahasiswa prodi panitia
        $pengajuan2 = \App\Models\PengajuanPembimbing::where('id_dosen2', $dosen->user_id)
            ->where('status', 'Diterima')
            ->whereHas('kelompok.anggota', function ($query) use ($idProdiPanitia) {
                $query->where('id_prodi', $idProdiPanitia);
            })
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

        // Ambil kuota per prodi panitia dari kuota_bimbingan_dosen
        $kuota = \App\Models\KuotaBimbinganDosen::where('id_dosen', $dosen->id_dosen)
            ->where('id_prodi', $idProdiPanitia)
            ->first();

        $dosen->kuota_bimbingan = $kuota ? $kuota->kuota_bimbingan : 0;
        $dosen->kuota_p2 = $kuota ? $kuota->kuota_p2 : 0;
    }

    return view('pages.panitia.kuota.index', compact('dosenList'));
}


    public function update(Request $request, $id)
    {
        $request->validate([
            'kuota_bimbingan' => 'required|integer|min:0',
            'kuota_p2' => 'required|integer|min:0',
        ]);

        $user = auth()->user();

        // Cek apakah user ini panitia prodi
        if ($user->role->name !== 'panitia' || $user->id_prodi === null) {
            return redirect()->route('panitia.kuota.index')->with('error', 'Hanya panitia prodi yang bisa mengubah kuota.');
        }

        $idProdiPanitia = $user->id_prodi;

        // Update / insert ke kuota_bimbingan_dosen
        $kuota = KuotaBimbinganDosen::updateOrCreate(
            [
                'id_dosen' => $id,
                'id_prodi' => $idProdiPanitia
            ],
            [
                'kuota_bimbingan' => $request->kuota_bimbingan,
                'kuota_p2' => $request->kuota_p2
            ]
        );

        return redirect()->route('panitia.kuota.index')->with('success', 'Kuota berhasil diperbarui.');
    }
}
