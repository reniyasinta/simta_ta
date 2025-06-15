<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanPembimbing;
use App\Models\KuotaBimbinganDosen;
use App\Models\Jadwal;
use App\Models\Surat;
use App\Models\Prodi;

class PimpinanController extends Controller
{
    public function index()
    {
        $totalPengajuan = PengajuanPembimbing::count();
        $totalSurat = Surat::count();
        $totalJadwal = Jadwal::count();
        $totalKuota = KuotaBimbinganDosen::count();
        $prodis = Prodi::all();

        return view('pages.pimpinan.dashboard', compact(
            'totalPengajuan', 'totalSurat', 'totalJadwal', 'totalKuota', 'prodis'
        ));
    }
}
