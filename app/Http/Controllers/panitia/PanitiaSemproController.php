<?php


namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanPembimbing;
use App\Models\Sempro;
use Illuminate\Support\Facades\Storage;

class PanitiaSemproController extends Controller
{
       public function index()
    {
        // Ambil pengajuan diterima (berarti sudah ada kelompok)
        $pengajuanList = PengajuanPembimbing::with([
            'kelompok.anggota1.mahasiswa',
            'kelompok.anggota2.mahasiswa',
            'kelompok.anggota3.mahasiswa'
        ])
        ->where('status', 'Diterima')
        ->get();

        // Ambil semua sempro
        $semproList = Sempro::with('pengajuan.kelompok')->get();

        return view('pages.panitia.sempro.index', compact('pengajuanList', 'semproList'));
    }
}
