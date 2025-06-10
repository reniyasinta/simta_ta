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


public function taConfig()
{
    $prodiId = Auth::user()->prodi_id;
    $linkConfig = TAConfig::where('id_prodi', $prodiId)
        ->where('nama_konfigurasi', 'link_drive_proyek_zip')
        ->first();

    return view('pages.panitia.sidang.link_drive', compact('linkConfig'));
}

public function taConfigUpdate(Request $request)
{
    $request->validate([
        'config_value' => 'required|url'
    ]);

    $prodiId = Auth::user()->prodi_id;

    $config = TAConfig::firstOrNew([
        'id_prodi' => $prodiId,
        'nama_konfigurasi' => 'link_drive_proyek_zip'
    ]);

    $config->config_value = $request->input('config_value');
    $config->save();

    return redirect()->route('panitia.sidang.link_drive')->with('success', 'Link Drive berhasil disimpan.');
}
public function submitFinal(Request $request, $id)
{
    $request->validate([
        'status_final' => 'required|in:Lengkap,Ditolak',
        'catatan_final' => 'nullable|string|max:255',
    ]);

    $sidang = Sidang::findOrFail($id);
    $sidang->status_final = $request->status_final;
    $sidang->catatan_final = $request->catatan_final ?? '-';
    $sidang->save();

    return redirect()->back()->with('success', 'Status sidang final berhasil diperbarui.');
}


}
