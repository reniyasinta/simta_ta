<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanPembimbing;
use App\Models\Dosen;

class PanitiaPengajuanController extends Controller
{
    public function index()
    {
        $pengajuanList = PengajuanPembimbing::with(['kelompok', 'dosen1', 'dosen2'])->get();
        $dosenList = Dosen::all();

        return view('pages.panitia.pengajuan.index', compact('pengajuanList', 'dosenList'));
    }

    public function edit($id)
    {
        $pengajuan = PengajuanPembimbing::with(['kelompok', 'dosen1', 'dosen2'])->findOrFail($id);
        $dosenList = Dosen::all();

        return view('pages.panitia.pengajuan.edit', compact('pengajuan', 'dosenList'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_dosen2' => 'required|exists:dosen,id_dosen',
        ]);

        $pengajuan = PengajuanPembimbing::findOrFail($id);
        $pengajuan->id_dosen2 = $request->id_dosen2;
        $pengajuan->save();

        return redirect()->route('panitia.pengajuan.index')->with('success', 'Dosen Pembimbing 2 berhasil ditetapkan.');
    }
}

