<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use App\Models\Prodi;

class PimpinanJadwalController extends Controller
{
public function seminar(Request $request)
{
    $prodiFilter = $request->get('prodi_id');
    $prodis = Prodi::all();

    $jadwals = Jadwal::with(['penguji1', 'penguji2', 'penguji3'])
        ->where('jenis_acara', 'seminar')
        ->when($prodiFilter, function ($query) use ($prodiFilter) {
            $namaProdi = Prodi::where('id', $prodiFilter)->value('nama_prodi');
            $query->where('prodi', $namaProdi);
        })
        ->orderBy('tanggal', 'desc')
        ->get();

    return view('pages.pimpinan.jadwal.seminar', compact('jadwals', 'prodis'));
}

    public function sidang(Request $request)
{
    $prodiFilter = $request->get('prodi_id');
    $prodis = Prodi::all();

    $jadwals = Jadwal::with(['penguji1', 'penguji2', 'penguji3'])
        ->where('jenis_acara', 'sidang')
        ->when($prodiFilter, function ($query) use ($prodiFilter) {
            // Cari nama prodi dari ID
            $namaProdi = Prodi::where('id', $prodiFilter)->value('nama_prodi');
            $query->where('prodi', $namaProdi);
        })
        ->orderBy('tanggal', 'desc')
        ->get();

    return view('pages.pimpinan.jadwal.sidang', compact('jadwals', 'prodis'));
}

    public function yudisium()
    {
        $jadwals = Jadwal::where('jenis_acara', 'yudisium')->orderBy('tanggal', 'desc')->get();
        return view('pages.pimpinan.jadwal.yudisium', compact('jadwals'));
    }

}
