<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Prodi;
use Illuminate\Http\Request;

class PimpinanJadwalController extends Controller
{
public function seminar(Request $request)
{
    $prodiFilter = $request->get('prodi_id');
    $prodis = Prodi::all();

    $jadwals = Jadwal::with([
            'penguji1',
            'penguji2',
            'penguji3',
            'pengajuan.kelompok.mahasiswa.prodi',
            'pengajuan.dosen1',
            'pengajuan.dosen2'
        ])
        ->where('jenis_acara', 'seminar')
        ->when($prodiFilter, function ($query) use ($prodiFilter) {
            $query->whereHas('pengajuan.kelompok.mahasiswa', function ($subQuery) use ($prodiFilter) {
                $subQuery->where('id_prodi', $prodiFilter);
            });
        })
        ->orderBy('tanggal', 'desc')
        ->get();

    return view('pages.pimpinan.jadwal.seminar', compact('jadwals', 'prodis', 'prodiFilter'));
}

public function sidang(Request $request)
{
    $prodiFilter = $request->get('prodi_id');
    $prodis = Prodi::all();

    $jadwals = Jadwal::with([
            'penguji1',
            'penguji2',
            'penguji3',
            'pengajuan.kelompok.mahasiswa.prodi',
            'pengajuan.dosen1',
            'pengajuan.dosen2'
        ])
        ->where('jenis_acara', 'sidang')
        ->when($prodiFilter, function ($query) use ($prodiFilter) {
            $query->whereHas('pengajuan.kelompok.mahasiswa', function ($subQuery) use ($prodiFilter) {
                $subQuery->where('id_prodi', $prodiFilter);
            });
        })
        ->orderBy('tanggal', 'asc')
        ->get();

    return view('pages.pimpinan.jadwal.sidang', compact('jadwals', 'prodis', 'prodiFilter'));
}


    public function yudisium(Request $request)
    {
        $prodis = Prodi::all();
        $prodiFilter = $request->get('prodi_id');

        $jadwals = Jadwal::with(['mahasiswa.prodi'])
            ->where('jenis_acara', 'yudisium')
            ->when($prodiFilter, function ($query) use ($prodiFilter) {
                $query->whereHas('mahasiswa', function ($subQuery) use ($prodiFilter) {
                    $subQuery->where('id_prodi', $prodiFilter);
                });
            })
            ->orderBy('tanggal', 'asc')
            ->get();

        return view('pages.pimpinan.jadwal.yudisium', compact('jadwals', 'prodis', 'prodiFilter'));
    }
}
