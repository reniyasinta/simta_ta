<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sempro;
use App\Models\Prodi;
use Illuminate\Support\Facades\Auth;

class DosenSemproController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $idProdiDosen = $user->dosen->id_prodi ?? null;

        // Group Mapping Prodi
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

        // Query dasar SEMPRO
        $query = Sempro::whereHas('pengajuan', function ($q) use ($userId) {
                $q->where('id_dosen1', $userId)
                  ->orWhere('id_dosen2', $userId);
            })
            ->with([
                'pengajuan.kelompok.anggota1.mahasiswa.prodi',
                'pengajuan.kelompok.anggota2.mahasiswa',
                'pengajuan.kelompok.anggota3.mahasiswa'
            ])
            ->whereHas('pengajuan.kelompok.anggota1.mahasiswa', function ($q) use ($allowedProdis) {
                $q->whereIn('id_prodi', $allowedProdis);
            });

        // Filter berdasarkan request prodi
        if ($request->filled('prodi')) {
            $query->whereHas('pengajuan.kelompok.anggota1.mahasiswa', function ($q) use ($request) {
                $q->where('id_prodi', $request->prodi);
            });
        }

        $sempros = $query->get();

        $availableProdis = Prodi::whereIn('id', $allowedProdis)->pluck('nama_prodi', 'id');

        return view('pages.dosen.sempro.index', compact('sempros', 'availableProdis'));
    }

    public function submit(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Disetujui,Revisi',
            'catatan' => 'nullable|string',
        ]);

        $sempro = Sempro::findOrFail($id);
        $user = Auth::user();
        $pengajuan = $sempro->pengajuan;

        if ($pengajuan->id_dosen1 == $user->id) {
            $sempro->status_proposal_ta_dospem1 = $request->status;
            $sempro->catatan_dospem1 = $request->catatan;
        } elseif ($pengajuan->id_dosen2 == $user->id) {
            $sempro->status_proposal_ta_dospem2 = $request->status;
            $sempro->catatan_dospem2 = $request->catatan;
        } else {
            return back()->with('error', 'Anda bukan dosen pembimbing untuk pengajuan ini.');
        }

        $sempro->save();

        return back()->with('success', 'Validasi Laporan SEMPRO berhasil disimpan.');
    }
}
