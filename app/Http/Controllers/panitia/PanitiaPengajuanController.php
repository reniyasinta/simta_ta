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
        $user = auth()->user();

        $pengajuanList = PengajuanPembimbing::with(['kelompok', 'dosen1', 'dosen2'])->get();

        // Filtering dosen berdasarkan prodi panitia (many-to-many)
        if ($user->role->name === 'panitia' && $user->id_prodi !== null) {
            $dosenList = Dosen::whereHas('prodis', function ($q) use ($user) {
                $q->where('id', $user->id_prodi);
            })->get();
        } else {
            // Panitia jurusan atau lainnya → tampilkan semua
            $dosenList = Dosen::all();
        }

        return view('pages.panitia.pengajuan.index', compact('pengajuanList', 'dosenList'));
    }


    public function edit($id)
    {
        $user = auth()->user();

        $pengajuan = PengajuanPembimbing::with(['kelompok', 'dosen1', 'dosen2'])->findOrFail($id);

        if ($user->role->name === 'panitia' && $user->id_prodi !== null) {
            $dosenList = Dosen::whereHas('prodis', function ($q) use ($user) {
                $q->where('id', $user->id_prodi);
            })->get();
        } else {
            $dosenList = Dosen::all();
        }

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

