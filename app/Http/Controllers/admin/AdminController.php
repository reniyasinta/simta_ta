<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Surat;
use App\Models\Dosen;
use App\Models\Mahasiswa;

class AdminController extends Controller
{
    public function index()
    {

        return $this->dashboard();
    }

    public function dashboard()
    {
        $jumlahMahasiswa = User::whereHas('mahasiswa')->count();
        $jumlahDosen = User::whereHas('dosen')->count();
        $jumlahPengajuanSurat = Surat::where('status', '!=', 'selesai')->count();


        $pengajuanTerbaru = Surat::with('mahasiswa.kelompok.anggota')->latest()->take(5)->get();

        return view('pages.admin.dashboard', compact(
            'jumlahMahasiswa',
            'jumlahDosen',
            'jumlahPengajuanSurat',
            'pengajuanTerbaru',
        ));
    }
}
