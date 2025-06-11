<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanPembimbing;
use App\Models\Prodi;
use App\Models\Dosen;

class ValidasiPengajuanController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
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
            // Jika dosen tidak memiliki prodi, tampilkan semua
            $allowedProdis = Prodi::pluck('id')->toArray();
        } else {
            // Ambil group yang sesuai dengan prodi dosen
            $groupProdi = collect($groupMapping)->first(function ($group) use ($idProdiDosen) {
                return in_array($idProdiDosen, $group);
            });

            if (!$groupProdi) {
                abort(403, 'Prodi dosen tidak termasuk dalam grup yang diizinkan.');
            }

            $allowedProdis = $groupProdi;
        }

        $query = PengajuanPembimbing::with([
            'kelompok.anggota1.mahasiswa.prodi',
            'kelompok.anggota2.mahasiswa',
            'kelompok.anggota3.mahasiswa',
            'dosen1.dosen',
        ])
        ->where('id_dosen1', $userId)
        ->whereHas('kelompok.anggota1.mahasiswa', function ($q) use ($allowedProdis) {
            $q->whereIn('id_prodi', $allowedProdis);
        });

        // Tambahan filter berdasarkan prodi dari request
        if ($request->filled('prodi')) {
            $query->whereHas('kelompok.anggota1.mahasiswa', function ($q) use ($request) {
                $q->where('id_prodi', $request->prodi);
            });
        }

        $pengajuan = $query->orderBy('created_at', 'desc')->get();
        $listProdi = Prodi::whereIn('id', $allowedProdis)->get();

        return view('pages.dosen.validasi.index', compact('pengajuan', 'listProdi'));
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
