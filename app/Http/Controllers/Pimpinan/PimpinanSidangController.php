<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use App\Models\Sidang;
use App\Models\Prodi;
use Illuminate\Http\Request;

class PimpinanSidangController extends Controller
{
    public function final(Request $request)
    {
        $prodiFilter = $request->get('prodi_id');

        $sidangList = Sidang::with(['kelompok.anggota.prodi'])
            ->when($prodiFilter, function ($query) use ($prodiFilter) {
                $query->whereHas('kelompok.anggota', function ($q) use ($prodiFilter) {
                    $q->where('id_prodi', $prodiFilter);
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $prodis = Prodi::all(); // untuk menampilkan semua prodi di dropdown filter

        return view('pages.pimpinan.sidang.final', compact('sidangList', 'prodis'));
    }
}
