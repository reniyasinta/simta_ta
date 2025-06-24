<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Surat;
use App\Models\Prodi;

class PimpinanSuratController extends Controller
{

public function index(Request $request)
{
    $prodis = Prodi::all();
    $prodiFilter = $request->get('prodi_id');

    $suratList = Surat::with(['mahasiswa'])
        ->when(!empty($prodiFilter), function ($query) use ($prodiFilter) {
            $query->whereHas('mahasiswa', function ($q) use ($prodiFilter) {
                $q->where('id_prodi', $prodiFilter);
            });
        })
        ->orderBy('created_at', 'desc')
        ->get();

    return view('pages.pimpinan.surat.index', compact('suratList', 'prodis'));
}
}
