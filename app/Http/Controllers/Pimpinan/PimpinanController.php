<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanPembimbing;
use App\Models\KuotaBimbinganDosen;
use App\Models\Jadwal;
use App\Models\Surat;
use App\Models\Prodi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class PimpinanController extends Controller
{
    public function index()
    {
        $totalPengajuan = PengajuanPembimbing::count();
        $totalSurat = Surat::count();
        $totalJadwal = Jadwal::count();
        $prodis = Prodi::all();

        return view('pages.pimpinan.dashboard', compact(
            'totalPengajuan', 'totalSurat', 'totalJadwal', 'prodis'
        ));
    }

    public function profile()
{
    $user = Auth::user();
    return view('pages.pimpinan.profile', compact('user'));
}

public function editProfile()
{
    $user = Auth::user();
    return view('pages.pimpinan.profile_edit', compact('user'));
}

public function updateProfile(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'name' => 'required|string|max:255',
        'nip' => 'required|string|max:30',
        'email' => 'required|email',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    // Simpan file foto jika ada
    if ($request->hasFile('foto')) {
        if ($user->foto && Storage::exists('public/uploads/foto_pimpinan/' . $user->foto)) {
            Storage::delete('public/uploads/foto_pimpinan/' . $user->foto);
        }

        $file = $request->file('foto');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/uploads/foto_pimpinan/', $filename);
        $user->foto = $filename;
    }

    // Update data
    $user->name = $request->name;
    $user->nip = $request->nip;
    $user->email = $request->email;
    $user->save();

    return redirect()->route('pimpinan.profile')->with('success', 'Profil berhasil diperbarui.');
}
}
