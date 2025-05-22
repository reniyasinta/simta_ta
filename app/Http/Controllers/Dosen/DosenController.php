<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Jadwal;
use App\Models\PengajuanPembimbing;
use App\Models\Dosen;

class DosenController extends Controller
{
    public function index()
    {

    $dosenId = Auth::id();

    $jadwals = Jadwal::where('penguji_1_id', $dosenId)
              ->orWhere('penguji_2_id', $dosenId)
              ->orWhere('penguji_3_id', $dosenId)
              ->get();

        return view('pages.dosen.dashboard', compact('jadwals'));
    }

    public function bimbingan()
    {
        $user = auth()->user();

        $dosen = Dosen::where('user_id', $user->id)->first();

        $sebagaiPembimbing1 = PengajuanPembimbing::with(['kelompok.anggota', 'dosen1'])
            ->where('id_dosen1', $user->id)
            ->where('status', 'Diterima')
            ->get();

        $sebagaiPembimbing2 = PengajuanPembimbing::with(['kelompok.anggota', 'dosen2'])
            ->where('id_dosen2', $user->id)
            ->where('status', 'Diterima')
            ->get();

        $totalBimbingan = $sebagaiPembimbing1->count() + $sebagaiPembimbing2->count();
        $kuota = $dosen->kuota_bimbingan ?? 0;

        return view('pages.dosen.bimbingan.index', compact(
            'sebagaiPembimbing1',
            'sebagaiPembimbing2',
            'totalBimbingan',
            'kuota'
        ));
    }

}
