<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanPembimbing;
use App\Models\Prodi;
use App\Models\Mahasiswa;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $idProdiDosen = $user->dosen->id_prodi ?? null;

        $groupMapping = [
            [1, 2], // TI & SIKC
            [3, 4], // Listrik & TRPE
            [5, 6], // Elka & TRO
        ];

        $allowedProdis = [];

        if ($idProdiDosen === null) {
            $allowedProdis = Prodi::pluck('id')->toArray();
        } else {
            $groupProdi = collect($groupMapping)->first(function ($group) use ($idProdiDosen) {
                return in_array($idProdiDosen, $group);
            });

            if (!$groupProdi) {
                abort(403, 'Prodi dosen tidak termasuk dalam grup yang diizinkan.');
            }

            $allowedProdis = $groupProdi;
        }

        // Ambil bimbingan sebagai dosen 1 & 2, dan filter berdasarkan anggota1 prodi
        $sebagaiPembimbing1 = PengajuanPembimbing::with(['kelompok.anggota.prodi'])
            ->where('id_dosen1', $user->id)
            ->whereHas('kelompok.anggota1.mahasiswa', function ($q) use ($allowedProdis, $request) {
                $q->whereIn('id_prodi', $allowedProdis);

                if ($request->filled('prodi')) {
                    $q->where('id_prodi', $request->prodi);
                }
            })->get();

        $sebagaiPembimbing2 = PengajuanPembimbing::with(['kelompok.anggota.prodi'])
            ->where('id_dosen2', $user->id)
            ->whereHas('kelompok.anggota1.mahasiswa', function ($q) use ($allowedProdis, $request) {
                $q->whereIn('id_prodi', $allowedProdis);

                if ($request->filled('prodi')) {
                    $q->where('id_prodi', $request->prodi);
                }
            })->get();

        $listProdi = Prodi::whereIn('id', $allowedProdis)->get();

        $kuota = $user->dosen->kuota_bimbingan ?? 0;
        $totalBimbingan = $sebagaiPembimbing1->sum(fn($p) => $p->kelompok?->anggota->count() ?? 0)
                            + $sebagaiPembimbing2->sum(fn($p) => $p->kelompok?->anggota->count() ?? 0);

        return view('pages.dosen.bimbingan.index', compact(
            'sebagaiPembimbing1', 'sebagaiPembimbing2', 'kuota', 'totalBimbingan', 'listProdi'
        ));
    }

        public function show($id)
    {
        $mahasiswa = Mahasiswa::with(['user', 'prodi'])->findOrFail($id);

        return view('pages.dosen.mahasiswa.show', compact('mahasiswa'));
    }
}

