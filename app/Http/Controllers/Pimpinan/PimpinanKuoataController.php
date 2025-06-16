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
        $prodiFilter = $request->get('prodi');

        $dosenList = Dosen::with('user', 'prodi')
            ->when($prodiFilter, function ($query) use ($prodiFilter) {
                $query->where('id_prodi', $prodiFilter);
            })
            ->get();

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
