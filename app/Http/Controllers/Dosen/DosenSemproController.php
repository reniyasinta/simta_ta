<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sempro;
use Illuminate\Support\Facades\Auth;

class DosenSemproController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil SEMPRO yg dospem1 atau dospem2 nya adalah user login
        $sempros = Sempro::whereHas('pengajuan', function ($q) use ($user) {
            $q->where('id_dosen1', $user->id)
              ->orWhere('id_dosen2', $user->id);
        })
        ->with('pengajuan.kelompok.anggota1.mahasiswa', 'pengajuan.kelompok.anggota2.mahasiswa', 'pengajuan.kelompok.anggota3.mahasiswa')
        ->get();

        return view('pages.dosen.sempro.index', compact('sempros'));
    }

    public function submit(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Disetujui,Ditolak',
            'catatan' => 'nullable|string',
        ]);

        $sempro = Sempro::findOrFail($id);
        $user = Auth::user();

        $pengajuan = $sempro->pengajuan;

        // Cek dospem1 atau dospem2
        if ($pengajuan->id_dosen1 == $user->id) {
            $sempro->status_dospem1 = $request->status;
            $sempro->catatan_dospem1 = $request->catatan;
        } elseif ($pengajuan->id_dosen2 == $user->id) {
            $sempro->status_dospem2 = $request->status;
            $sempro->catatan_dospem2 = $request->catatan;
        } else {
            return back()->with('error', 'Anda bukan dosen pembimbing untuk pengajuan ini.');
        }

        $sempro->save();

        return back()->with('success', 'Validasi berhasil disimpan.');
    }
}
