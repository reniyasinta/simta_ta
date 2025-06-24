<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\KuotaBimbinganDosen;
use App\Models\PengajuanPembimbing;
use App\Models\Prodi;
use Illuminate\Http\Request;

class PimpinanKuotaController extends Controller
{
     public function index(Request $request)
{
    $prodis = Prodi::all();
    $prodiFilter = $request->get('prodi_id');

    // Group mapping
    $groupMapping = [
        1 => [1, 2], // TI & SIKC
        2 => [3, 4], // Listrik & TRPE
        3 => [5, 6], // Elka & TRO
    ];

    $dosenQuery = Dosen::with('user', 'prodi');

    // Filter berdasarkan grup prodi
    if (!empty($prodiFilter)) {
        // Temukan grup mana prodi ini masuk
        $group = collect($groupMapping)->firstWhere(fn($group) => in_array($prodiFilter, $group));
        $filteredProdis = $group ?? [$prodiFilter]; // fallback ke 1 prodi kalau tidak ada di mapping

        $dosenQuery->whereIn('id_prodi', $filteredProdis);
    }

    $dosenList = $dosenQuery->get();

    foreach ($dosenList as $dosen) {
        $jumlah = 0;

        $pengajuan1 = PengajuanPembimbing::where('id_dosen1', $dosen->user_id)
            ->where('status', 'Diterima')
            ->with('kelompok')
            ->get();

        $pengajuan2 = PengajuanPembimbing::where('id_dosen2', $dosen->user_id)
            ->where('status', 'Diterima')
            ->with('kelompok')
            ->get();

        foreach ($pengajuan1 as $p) {
            $jumlah += $p->kelompok?->jumlah_anggota ?? 0;
        }
        foreach ($pengajuan2 as $p) {
            $jumlah += $p->kelompok?->jumlah_anggota ?? 0;
        }

        $dosen->bimbingan_terpakai = $jumlah;

        $kuota = KuotaBimbinganDosen::where('id_dosen', $dosen->id_dosen)->first();
        $dosen->kuota_bimbingan = $kuota?->kuota_bimbingan ?? 0;
        $dosen->kuota_p2 = $kuota?->kuota_p2 ?? 0;
    }

    return view('pages.pimpinan.kuota.index', compact('dosenList', 'prodis'));
}

}

