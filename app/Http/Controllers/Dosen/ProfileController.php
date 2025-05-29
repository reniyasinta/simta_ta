<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Dosen;
use App\Models\PengajuanPembimbing;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $dosen = Dosen::with('prodi')->where('user_id', $user->id)->firstOrFail();

        // Hitung kuota terpakai
        $jumlah1 = \App\Models\PengajuanPembimbing::where('id_dosen1', $user->id)
            ->where('status', 'Diterima')
            ->with('kelompok')
            ->get()
            ->sum(function ($pengajuan) {
                return $pengajuan->kelompok?->anggota->count() ?? 0;
            });

        $jumlah2 = \App\Models\PengajuanPembimbing::where('id_dosen2', $user->id)
            ->where('status', 'Diterima')
            ->with('kelompok')
            ->get()
            ->sum(function ($pengajuan) {
                return $pengajuan->kelompok?->anggota->count() ?? 0;
            });

        $dosen->kuota_terpakai = $jumlah1 + $jumlah2;

        return view('pages.dosen.profile', compact('user', 'dosen'));
    }

        public function update(Request $request)
        {
            $request->validate([
                'nama_dosen' => 'required|string|max:255',
                'nip_dosen' => 'required|string|max:100',
                'keahlian' => 'nullable|string|max:255',
                'no_telp' => 'nullable|string|max:50',
                'email' => 'required|email|max:255',
                'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $user = Auth::user();
            $dosen = Dosen::where('user_id', $user->id)->firstOrFail();

            // === Sinkron ke tabel users ===
            $user->email = $request->email;
            $user->name = $request->nama_dosen;
            $user->nip = $request->nip_dosen;
            $user->save();

            // === Upload foto baru jika ada ===
            if ($request->hasFile('foto')) {
                if ($dosen->foto && file_exists(public_path('uploads/foto_dosen/' . $dosen->foto))) {
                    unlink(public_path('uploads/foto_dosen/' . $dosen->foto));
                }

                $file = $request->file('foto');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/foto_dosen'), $filename);
                $dosen->foto = $filename;
            }

            // === Update tabel dosen ===
            $dosen->nama_dosen = $request->nama_dosen;
            $dosen->nip_dosen = $request->nip_dosen;
            $dosen->keahlian = $request->keahlian;
            $dosen->no_telp = $request->no_telp;
            $dosen->save();

            return redirect()->route('dosen.profile')->with('success', 'Profil berhasil diperbarui.');
        }


        public function edit()
    {
        $user = Auth::user();
        $dosen = Dosen::with('prodi')->where('user_id', $user->id)->firstOrFail();
        return view('pages.dosen.profile_edit', compact('user', 'dosen'));
    }

}
