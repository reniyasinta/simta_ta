<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\KuotaBimbinganDosen;

class DosenKuotaController extends Controller
{
public function index()
{
    $user = auth()->user();
    $idProdiPanitia = $user->id_prodi;

    // Mapping Group Prodi
    $groupMapping = [
        [1, 2], // TI & SIKC
        [3, 4], // Listrik & TRPE
        [5, 6], // Elka & TRO
    ];

    if ($idProdiPanitia === null) {
        // === PANITIA JURUSAN ===
        // Tampilkan semua dosen semua prodi
        $dosenList = Dosen::with('user', 'prodi')->get();
    } else {
        // === PANITIA PRODI ===
        // Cari group sesuai prodi panitia
        $groupProdi = collect($groupMapping)->first(function ($group) use ($idProdiPanitia) {
            return in_array($idProdiPanitia, $group);
        });

        if (!$groupProdi) {
            // Prodi panitia tidak termasuk group
            abort(403, 'Prodi panitia tidak termasuk dalam grup yang diizinkan.');
        }

        // Ambil dosen di group prodi
        $dosenList = Dosen::with('user', 'prodi')
            ->whereIn('id_prodi', $groupProdi)
            ->get();
    }

    // Hitung bimbingan & kuota
    foreach ($dosenList as $dosen) {
        $jumlah = 0;

        // Dosen sebagai Pembimbing 1
        $pengajuan1 = \App\Models\PengajuanPembimbing::where('id_dosen1', $dosen->user_id)
            ->where('status', 'Diterima')
            ->when($idProdiPanitia !== null, function ($query) use ($idProdiPanitia) {
                // Filter prodi hanya kalau panitia prodi
                $query->whereHas('kelompok.anggota', function ($q) use ($idProdiPanitia) {
                    $q->where('id_prodi', $idProdiPanitia);
                });
            })
            ->with('kelompok')
            ->get();

        // Dosen sebagai Pembimbing 2
        $pengajuan2 = \App\Models\PengajuanPembimbing::where('id_dosen2', $dosen->user_id)
            ->where('status', 'Diterima')
            ->when($idProdiPanitia !== null, function ($query) use ($idProdiPanitia) {
                // Filter prodi hanya kalau panitia prodi
                $query->whereHas('kelompok.anggota', function ($q) use ($idProdiPanitia) {
                    $q->where('id_prodi', $idProdiPanitia);
                });
            })
            ->with('kelompok')
            ->get();

        // Hitung jumlah anggota
        foreach ($pengajuan1 as $pengajuan) {
            $jumlah += $pengajuan->kelompok?->jumlah_anggota ?? 0;
        }

        foreach ($pengajuan2 as $pengajuan) {
            $jumlah += $pengajuan->kelompok?->jumlah_anggota ?? 0;
        }

        $dosen->bimbingan_terpakai = $jumlah;

        // Ambil kuota
        $kuotaQuery = \App\Models\KuotaBimbinganDosen::where('id_dosen', $dosen->id_dosen);

        if ($idProdiPanitia !== null) {
            // Panitia prodi → ambil kuota per prodi panitia
            $kuotaQuery->where('id_prodi', $idProdiPanitia);
        } else {
            // Panitia jurusan → bebas, ambil kuota pertama saja (jika ada)
            $kuotaQuery->orderBy('id_prodi');
        }

        $kuota = $kuotaQuery->first();

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
        KuotaBimbinganDosen::updateOrCreate(
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
