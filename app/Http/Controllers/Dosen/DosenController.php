<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Jadwal;
use App\Models\PengajuanPembimbing;
use App\Models\Dosen;
use App\Models\KuotaBimbinganDosen;

class DosenController extends Controller
{
public function index()
{
    $user = Auth::user();
    $dosen = Dosen::with('prodi')->where('user_id', $user->id)->firstOrFail();

    $kuota = KuotaBimbinganDosen::where('id_dosen', $dosen->id_dosen)->sum('kuota_bimbingan');

    // Hitung jumlah mahasiswa bimbingan
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

    // Semua jadwal (untuk ditampilkan di dashboard utama)
    $jadwals = Jadwal::where(function ($query) use ($dosen) {
        $query->where('penguji_1_id', $dosen->user_id)
            ->orWhere('penguji_2_id', $dosen->user_id)
            ->orWhere('penguji_3_id', $dosen->user_id);
    })->orderBy('tanggal', 'asc')
      ->orderBy('jam_mulai', 'asc')
      ->get();

    // Jadwal Seminar saja
    $jadwalSeminar = Jadwal::where('jenis_acara', 'seminar')
        ->where(function ($query) use ($dosen) {
            $query->where('penguji_1_id', $dosen->user_id)
                ->orWhere('penguji_2_id', $dosen->user_id)
                ->orWhere('penguji_3_id', $dosen->user_id);
        })
        ->orderBy('tanggal', 'asc')
        ->orderBy('jam_mulai', 'asc')
        ->get();

    // Jadwal Sidang saja
    $jadwalSidang = Jadwal::where('jenis_acara', 'sidang')
        ->where(function ($query) use ($dosen) {
            $query->where('penguji_1_id', $dosen->user_id)
                ->orWhere('penguji_2_id', $dosen->user_id)
                ->orWhere('penguji_3_id', $dosen->user_id);
        })
        ->orderBy('tanggal', 'asc')
        ->orderBy('jam_mulai', 'asc')
        ->get();

    // Total masing-masing
    $totalJadwalSeminar = $jadwalSeminar->count();
    $totalJadwalSidang = $jadwalSidang->count();

    return view('pages.dosen.dashboard', compact(
        'dosen',
        'totalBimbingan',
        'kuota',
        'jadwals',
        'jadwalSeminar',
        'jadwalSidang',
        'totalJadwalSeminar',
        'totalJadwalSidang'
    ));
}

public function bimbingan(Request $request)
{
    $user = auth()->user();
    $dosen = Dosen::where('user_id', $user->id)->first();

    // Group Mapping
    $groupMapping = [
        [1, 2], [3, 4], [5, 6],
    ];

    $idProdiDosen = $dosen->id_prodi ?? null;
    $allowedProdis = [];

    if ($idProdiDosen === null) {
        $allowedProdis = \App\Models\Prodi::pluck('id')->toArray();
    } else {
        $groupProdi = collect($groupMapping)->first(fn($group) => in_array($idProdiDosen, $group));
        $allowedProdis = $groupProdi ?: [];
    }

    $prodiFilter = $request->prodi;

    $sebagaiPembimbing1 = PengajuanPembimbing::with(['kelompok.anggota.prodi', 'dosen1'])
        ->where('id_dosen1', $user->id)
        ->where('status', 'Diterima')
        ->when($prodiFilter, function ($query) use ($prodiFilter) {
            $query->whereHas('kelompok.anggota1.mahasiswa', fn($q) => $q->where('id_prodi', $prodiFilter));
        })
        ->paginate(5, ['*'], 'bimbingan1_page');

    $sebagaiPembimbing2 = PengajuanPembimbing::with(['kelompok.anggota.prodi', 'dosen2'])
        ->where('id_dosen2', $user->id)
        ->where('status', 'Diterima')
        ->when($prodiFilter, function ($query) use ($prodiFilter) {
            $query->whereHas('kelompok.anggota1.mahasiswa', fn($q) => $q->where('id_prodi', $prodiFilter));
        })
        ->paginate(5, ['*'], 'bimbingan2_page');

    $totalBimbingan = $sebagaiPembimbing1->sum(fn($p) => $p->kelompok?->anggota->count() ?? 0)
        + $sebagaiPembimbing2->sum(fn($p) => $p->kelompok?->anggota->count() ?? 0);
    $kuota = \App\Models\KuotaBimbinganDosen::where('id_dosen', $dosen->id_dosen)->sum('kuota_bimbingan');

    $listProdi = \App\Models\Prodi::whereIn('id', $allowedProdis)->pluck('nama_prodi', 'id');

    return view('pages.dosen.bimbingan.index', compact(
        'sebagaiPembimbing1',
        'sebagaiPembimbing2',
        'totalBimbingan',
        'kuota',
        'listProdi'
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
