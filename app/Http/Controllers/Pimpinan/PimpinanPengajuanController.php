<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanPembimbing;
use App\Models\Prodi;


class PimpinanPengajuanController extends Controller
{
    public function index(Request $request)
    {
        $prodis = Prodi::all();
        $prodiFilter = $request->get('prodi');

        $pengajuanList = PengajuanPembimbing::with(['dosen1', 'dosen2', 'kelompok.anggota1.mahasiswa'])
            ->where('status', 'Diterima')
            ->when($prodiFilter, function ($query) use ($prodiFilter) {
                $query->whereHas('kelompok.anggota1.mahasiswa', function ($q) use ($prodiFilter) {
                    $q->where('id_prodi', $prodiFilter);
                });
            })
            ->get();

        return view('pages.pimpinan.pengajuan.index', compact('pengajuanList', 'prodis'));
    }
}

