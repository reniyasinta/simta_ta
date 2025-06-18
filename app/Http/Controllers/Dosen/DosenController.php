<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Jadwal;
use App\Models\PengajuanPembimbing;
use App\Models\Dosen;

class DosenController extends Controller
{
public function index()
{
    $user = Auth::user();
    $dosen = Dosen::with('prodi')->where('user_id', $user->id)->firstOrFail();

    // Hitung jumlah mahasiswa yang dibimbing
    $jumlah1 = PengajuanPembimbing::where('id_dosen1', $user->id)
        ->where('status', 'Diterima')
        ->with('kelompok')
        ->get()
        ->sum(fn($p) => $p->kelompok?->anggota->count() ?? 0);

    $jumlah2 = PengajuanPembimbing::where('id_dosen2', $user->id)
        ->where('status', 'Diterima')
        ->with('kelompok')
        ->get()
        ->sum(fn($p) => $p->kelompok?->anggota->count() ?? 0);

    $totalBimbingan = $jumlah1 + $jumlah2;
    $kuota = $dosen->kuota_bimbingan ?? 0;

    $jadwals = Jadwal::where(function ($query) use ($dosen) {
        $query->where('penguji_1_id', $dosen->user_id)
            ->orWhere('penguji_2_id', $dosen->user_id)
            ->orWhere('penguji_3_id', $dosen->user_id);
    })->orderBy('tanggal', 'asc')
    ->orderBy('jam_mulai', 'asc')
    ->get();

    return view('pages.dosen.dashboard', compact('dosen', 'totalBimbingan', 'kuota', 'jadwals'));
}


    public function bimbingan()
    {
        $user = auth()->user();

        $dosen = Dosen::where('user_id', $user->id)->first();

        $sebagaiPembimbing1 = PengajuanPembimbing::with(['kelompok.anggota', 'dosen1'])
            ->where('id_dosen1', $user->id)
            ->where('status', 'Diterima')
            ->get();

        $sebagaiPembimbing2 = PengajuanPembimbing::with(['kelompok.anggota', 'dosen2'])
            ->where('id_dosen2', $user->id)
            ->where('status', 'Diterima')
            ->get();

        $totalBimbingan = $sebagaiPembimbing1->sum(fn($p) => $p->kelompok?->anggota->count() ?? 0)
         + $sebagaiPembimbing2->sum(fn($p) => $p->kelompok?->anggota->count() ?? 0);
        $kuota = $dosen->kuota_bimbingan ?? 0;

        $groupMapping = [
            [1, 2], // TI & SIKC
            [3, 4], // Listrik & TRPE
            [5, 6], // Elka & TRO
        ];

        $idProdiDosen = $dosen->id_prodi ?? null;
        $allowedProdis = [];

        if ($idProdiDosen === null) {
            $allowedProdis = \App\Models\Prodi::pluck('id')->toArray();
        } else {
            $groupProdi = collect($groupMapping)->first(function ($group) use ($idProdiDosen) {
                return in_array($idProdiDosen, $group);
            });

            $allowedProdis = $groupProdi ?: [];
        }

        $listProdi = \App\Models\Prodi::whereIn('id', $allowedProdis)->get();


        return view('pages.dosen.bimbingan.index', compact(
            'sebagaiPembimbing1',
            'sebagaiPembimbing2',
            'totalBimbingan',
            'kuota',
            'listProdi' // ✅ penting!
        ));
    }
    
public function kuotaPerProdi()
{
    $prodis = \App\Models\Prodi::with(['dosens' => function ($query) {
        $query->select('id_dosen', 'id_prodi', 'kuota_bimbingan', 'user_id');
    }])->get();

    $kuota = [];

    foreach ($prodis as $prodi) {
        $maks = $prodi->dosens->sum('kuota_bimbingan');
        $terisi = 0;

        foreach ($prodi->dosens as $dosen) {
        $terisi += PengajuanPembimbing::where('id_dosen1', $dosen->user_id)
            ->where('status', 'Diterima')
            ->with('kelompok')
            ->get()
            ->sum(fn($p) => $p->kelompok?->anggota->count() ?? 0);

        $terisi += PengajuanPembimbing::where('id_dosen2', $dosen->user_id)
            ->where('status', 'Diterima')
            ->with('kelompok')
            ->get()
            ->sum(fn($p) => $p->kelompok?->anggota->count() ?? 0);
        }

        $kuota[] = [
            'nama_prodi' => $prodi->nama_prodi,
            'maks' => $maks,
            'terisi' => $terisi,
            'sisa' => $maks - $terisi,
        ];
    }

    return view('pages.dosen.kuota-per-prodi', compact('kuota'));
}

}
