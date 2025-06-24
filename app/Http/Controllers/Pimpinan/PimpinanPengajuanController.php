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
    $prodiFilter = $request->get('prodi_id');

    $pengajuanList = PengajuanPembimbing::with([
        'dosen1.dosen',
        'dosen2.dosen',
        'kelompok.anggota.prodi',
    ])
    ->where('status', 'Diterima')
    ->when(!empty($prodiFilter), function ($query) use ($prodiFilter) {
        $query->whereHas('kelompok.anggota', function ($q) use ($prodiFilter) {
            $q->where('id_prodi', $prodiFilter); // langsung dari mahasiswa
        });
    })
    ->get();

    return view('pages.pimpinan.pengajuan.index', compact('pengajuanList', 'prodis'));
}


}

