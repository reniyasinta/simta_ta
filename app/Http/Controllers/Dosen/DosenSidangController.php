<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Sidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DosenSidangController extends Controller
{
    public function draft()
    {
        $user = Auth::user();
        $sidangList = Sidang::where(function ($query) use ($user) {
            $query->where('id_dosen1', $user->id)
                  ->orWhere('id_dosen2', $user->id);
        })->with('kelompok.anggota1.mahasiswa', 'kelompok.anggota2.mahasiswa', 'kelompok.anggota3.mahasiswa')->get();

        return view('pages.dosen.sidang.draft.index', compact('sidangList'));
    }

    public function updateStatusDraft(Request $request, $id_sidang)
    {
        $request->validate([
            'status_draft' => 'required|in:Revisi,Disetujui',
            'catatan' => 'nullable|string',
        ]);

        $user = Auth::user();
        $sidang = Sidang::findOrFail($id_sidang);

        if ($sidang->id_dosen1 == $user->id) {
            $sidang->status_draft_dosen1 = $request->status_draft;
            $sidang->catatan_draft_dosen1 = $request->catatan;
        } elseif ($sidang->id_dosen2 == $user->id) {
            $sidang->status_draft_dosen2 = $request->status_draft;
            $sidang->catatan_draft_dosen2 = $request->catatan;
        } else {
            return back()->with('error', 'Anda bukan dosen pembimbing sidang ini.');
        }

        $sidang->save();

        return back()->with('success', 'Status draft berhasil diperbarui.');
    }


    public function revisi()
    {
        $user = Auth::user();

        // Ambil sidang di mana dosen sebagai penguji 1/2/3, + relasi penguji (biar hemat query)
        $sidangList = Sidang::with('penguji1', 'penguji2', 'penguji3', 'kelompok')
            ->where(function ($query) use ($user) {
                $query->where('penguji_1_id', $user->id)
                    ->orWhere('penguji_2_id', $user->id)
                    ->orWhere('penguji_3_id', $user->id);
            })->get();

        return view('pages.dosen.sidang.revisi.index', compact('sidangList', 'user'));
    }


    public function updateStatusRevisi(Request $request, $id_sidang, $penguji_ke)
    {
        $request->validate([
            'status_revisi' => 'required|in:Menunggu,Revisi,Disetujui',
            'catatan_revisi' => 'nullable|string',
        ]);

        $user = Auth::user();
        $sidang = Sidang::findOrFail($id_sidang);

        // Cek penguji keberapa
        if ($penguji_ke == 1 && $sidang->penguji_1_id == $user->id) {
            $sidang->status_revisi_penguji_1 = $request->status_revisi;
            $sidang->catatan_penguji_1 = $request->catatan_revisi;
        } elseif ($penguji_ke == 2 && $sidang->penguji_2_id == $user->id) {
            $sidang->status_revisi_penguji_2 = $request->status_revisi;
            $sidang->catatan_penguji_2 = $request->catatan_revisi;
        } elseif ($penguji_ke == 3 && $sidang->penguji_3_id == $user->id) {
            $sidang->status_revisi_penguji_3 = $request->status_revisi;
            $sidang->catatan_penguji_3 = $request->catatan_revisi;
        } else {
            return back()->with('error', 'Anda bukan penguji untuk sidang ini.');
        }

        $sidang->save();

        return back()->with('success', 'Status Revisi berhasil diperbarui.');
    }

}
