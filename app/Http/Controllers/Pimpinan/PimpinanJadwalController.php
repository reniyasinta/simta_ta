<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Illuminate\Http\Request;

class PimpinanJadwalController extends Controller
{
    public function seminar()
    {
        $jadwals = Jadwal::where('jenis_acara', 'seminar')->orderBy('tanggal', 'desc')->get();
        return view('pages.pimpinan.jadwal.seminar', compact('jadwals'));
    }

    public function sidang()
    {
        $jadwals = Jadwal::where('jenis_acara', 'sidang')->orderBy('tanggal', 'desc')->get();
        return view('pages.pimpinan.jadwal.sidang', compact('jadwals'));
    }

    public function yudisium()
    {
        $jadwals = Jadwal::where('jenis_acara', 'yudisium')->orderBy('tanggal', 'desc')->get();
        return view('pages.pimpinan.jadwal.yudisium', compact('jadwals'));
    }
}
