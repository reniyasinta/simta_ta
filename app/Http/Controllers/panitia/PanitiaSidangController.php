<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sidang;
use App\Models\TAConfig;


class PanitiaSidangController extends Controller
{
    public function draft()
    {
        $sidangList = Sidang::with('mahasiswa.user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.panitia.sidang.draft', compact('sidangList'));
    }
  public function revisi()
    {
        $sidangList = Sidang::with('mahasiswa.user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.panitia.sidang.revisi', compact('sidangList'));
    }

    public function final()
    {
        $sidangList = Sidang::with([
            'kelompok.anggota.prodi',
        ])->orderBy('created_at', 'desc')->get();

        return view('pages.panitia.sidang.final', compact('sidangList'));
    }


public function submitFinal(Request $request, $id_sidang)
{
    $request->validate([
        'status_final' => 'required|in:Disetujui,Ditolak',
        'catatan_final' => 'nullable|string',
    ]);

    $sidang = Sidang::findOrFail($id_sidang);
    $sidang->status_final = $request->status_final;
    $sidang->catatan_final = $request->catatan_final;
    $sidang->save();

    return redirect()->back()->with('success', 'Validasi final berhasil.');
}
public function formDrive($id)
{
    $sidang = Sidang::findOrFail($id);
    return view('pages.panitia.sidang.drive', compact('sidang'));
}


}
