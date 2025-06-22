<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Dosen;
use App\Models\PengajuanPembimbing;
use App\Models\KuotaBimbinganDosen;

class ProfileController extends Controller
{
public function index()
{
    $user = Auth::user();
    $dosen = Dosen::with('prodi')->where('user_id', $user->id)->firstOrFail();

    // Ambil kuota dari tabel kuota_bimbingan_dosen
    $kuotaRecord = KuotaBimbinganDosen::where('id_dosen', $dosen->id_dosen)->first();
    $kuota = $kuotaRecord->kuota_bimbingan ?? 0; // Kuota maksimal dosen

    // Hitung total bimbingan (sebagai pembimbing 1 dan 2)
    $jumlah1 = PengajuanPembimbing::where('id_dosen1', $dosen->id_dosen)
        ->where('status', 'Diterima')
        ->count(); // Menggunakan count() langsung untuk jumlah mahasiswa yang dibimbing dosen 1

    $jumlah2 = PengajuanPembimbing::where('id_dosen2', $dosen->id_dosen)
        ->where('status', 'Diterima')
        ->count(); // Menggunakan count() langsung untuk jumlah mahasiswa yang dibimbing dosen 2

    $totalBimbingan = $jumlah1 + $jumlah2; // Total bimbingan yang dilakukan oleh dosen

    // Hitung kuota terpakai
    $kuotaTerpakai = $totalBimbingan;

    // Update data yang akan ditampilkan
    $dosen->kuota_bimbingan = $kuota;
    $dosen->kuota_terpakai = $kuotaTerpakai;

    // Kirimkan ke view
    return view('pages.dosen.profile', compact('user', 'dosen', 'totalBimbingan', 'kuota'));
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
