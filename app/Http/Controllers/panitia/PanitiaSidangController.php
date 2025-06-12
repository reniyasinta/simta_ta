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

public function saveDrive(Request $request, $id)
{
    $request->validate([
        'link_drive_proyek' => 'required|url'
    ]);

    $sidang = Sidang::findOrFail($id);
    $sidang->link_drive_proyek = $request->link_drive_proyek;
    $sidang->save();

    return redirect()->route('sidang.final')->with('success', 'Link Drive berhasil disimpan.');
}
public function deleteDrive($id_sidang)
{
    $sidang = Sidang::findOrFail($id_sidang);
    $sidang->link_drive_proyek = null;
    $sidang->save();

    return redirect()->route('sidang.final')->with('success', 'Link drive berhasil dihapus.');
}

}
