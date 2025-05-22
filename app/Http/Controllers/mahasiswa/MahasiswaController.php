<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\PengajuanPembimbing;
use Illuminate\Support\Facades\Storage;

class MahasiswaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            return redirect()->back()->withErrors(['Anda belum terdaftar sebagai mahasiswa.']);
        }

        $jadwals = Jadwal::where('id_mhs', $mahasiswa->id_mhs)->get();

        $dosens = Dosen::with('user', 'prodi')->get();

        foreach ($dosens as $dosen) {
            $jumlahSebagai1 = PengajuanPembimbing::where('id_dosen1', $dosen->user_id)
                ->where('status', 'Diterima')
                ->count();

            $jumlahSebagai2 = PengajuanPembimbing::where('id_dosen2', $dosen->user_id)
                ->where('status', 'Diterima')
                ->count();

            $dosen->kuota_terpakai = $jumlahSebagai1 + $jumlahSebagai2;
            $dosen->kuota_total = $dosen->kuota_bimbingan ?? 0;
        }

        return view('pages.mahasiswa.dashboard', compact('jadwals', 'dosens'));
    }

    public function profile()
    {
        $mahasiswa = Mahasiswa::with(['user.prodi'])
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('pages.mahasiswa.profile', compact('mahasiswa'));
    }

    public function editProfile()
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::with('prodi')->where('user_id', $user->id)->firstOrFail();
        return view('pages.mahasiswa.profile_edit', compact('user', 'mahasiswa'));
    }

 public function updateProfile(Request $request)
{
    $user = Auth::user();
    $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();

    $request->validate([
        'nama_mhs' => 'required|string|max:255',
        'nim_mhs' => 'required|string|max:255|unique:users,nim,' . $user->id,
        'semester' => 'required|integer',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    // === Sinkron ke tabel users ===
    $user->email = $request->email;
    $user->name = $request->nama_mhs;
    $user->nim = $request->nim_mhs;
    $user->save();

    // === Handle upload foto baru ===
    if ($request->hasFile('foto')) {
        if ($mahasiswa->foto && Storage::exists(str_replace('storage/', '', $mahasiswa->foto))) {
            Storage::delete(str_replace('storage/', '', $mahasiswa->foto));
        }

        $path = $request->file('foto')->store('uploads/foto_mahasiswa', 'public');
        $mahasiswa->foto = 'storage/' . $path;
    }

    // === Update tabel mahasiswa ===
    $mahasiswa->nama_mhs = $request->nama_mhs;
    $mahasiswa->nim_mhs = $request->nim_mhs;
    $mahasiswa->semester = $request->semester;
    $mahasiswa->id_prodi = $user->id_prodi;
    $mahasiswa->save();

    return redirect()->route('mahasiswa.profile')->with('success', 'Profil berhasil diperbarui.');
}

}
