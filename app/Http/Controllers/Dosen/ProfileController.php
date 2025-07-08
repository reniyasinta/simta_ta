<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Dosen;
use App\Models\PengajuanPembimbing;
use App\Models\KuotaBimbinganDosen;
use App\Models\Mahasiswa;
use App\Models\Prodi;

class ProfileController extends Controller
{
public function index()
{
    $user = Auth::user();
    $dosen = Dosen::with('prodi')->where('user_id', $user->id)->firstOrFail();

    // Ambil semua kuota bimbingan dosen ini dari semua prodi
    $kuotaRecords = KuotaBimbinganDosen::with('prodi')
        ->where('id_dosen', $dosen->id_dosen)
        ->get();

    $kuota = $kuotaRecords->sum('kuota_bimbingan');

        // Ambil semua pengajuan sebagai Pembimbing 1
        $pengajuan = PengajuanPembimbing::where('id_dosen1', $dosen->user_id)
            ->where('status', 'Diterima')
            ->with('kelompok.anggota1.mahasiswa', 'kelompok.anggota2.mahasiswa', 'kelompok.anggota3.mahasiswa')
            ->get();

        // Hitung total bimbingan dari kelompok (hanya pembimbing 1)
        $totalBimbingan = $pengajuan->sum(function ($p) {
            return $p->kelompok?->anggota->count() ?? 0;
        });

        // Hitung kuota terisi per prodi (hanya pembimbing 1)
        $kuotaPerProdi = $kuotaRecords->map(function ($item) use ($pengajuan) {
            $count = 0;

            foreach ($pengajuan as $p) {
                if ($p->kelompok && $p->kelompok->anggota) {
                    foreach ($p->kelompok->anggota as $anggota) {
                        if ($anggota && $anggota->id_prodi == $item->id_prodi) {
                            $count++;
                        }
                    }
                }
            }

        $item->terisi = $count;
        return $item;
    });

    return view('pages.dosen.profile', compact(
        'user',
        'dosen',
        'totalBimbingan',
        'kuota',
        'kuotaPerProdi'
    ));
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

        // === Upload foto baru jika ada (seperti mahasiswa) ===
        if ($request->hasFile('foto')) {
            // Hapus file lama kalau ada
            if ($dosen->foto && Storage::disk('public')->exists('uploads/foto_dosen/' . $dosen->foto)) {
                Storage::disk('public')->delete('uploads/foto_dosen/' . $dosen->foto);
            }

            // Simpan file baru dengan nama unik
            $fileName = uniqid() . '.' . $request->file('foto')->getClientOriginalExtension();
            $request->file('foto')->storeAs('uploads/foto_dosen', $fileName, 'public');
            $dosen->foto = $fileName; // hanya simpan nama file
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
