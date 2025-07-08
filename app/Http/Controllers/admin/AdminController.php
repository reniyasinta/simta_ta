<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Surat;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {

        return $this->dashboard();
    }

    public function dashboard()
    {
        $jumlahMahasiswa = User::whereHas('mahasiswa')->count();
        $jumlahDosen = User::whereHas('dosen')->count();
        $jumlahPengajuanSurat = Surat::where('status', '!=', 'selesai')->count();


        $pengajuanTerbaru = Surat::with('mahasiswa.kelompok.anggota')->latest()->take(5)->get();

        return view('pages.admin.dashboard', compact(
            'jumlahMahasiswa',
            'jumlahDosen',
            'jumlahPengajuanSurat',
            'pengajuanTerbaru',
        ));
    }

    public function profile()
    {
        $user = auth()->user();
        return view('pages.admin.profile', compact('user'));
    }

public function editProfile()
{
    $user = Auth::user(); // Tidak perlu $user->admin
    return view('pages.admin.profile_edit', compact('user'));
}


public function updateProfile(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'name' => 'required|string|max:255',
        'nip' => 'required|string|max:30|unique:users,nip,' . $user->id,
        'email' => 'required|email|unique:users,email,' . $user->id,
    ], [
        'nip.unique' => 'NIP sudah digunakan, gunakan NIP lain.',
        'email.unique' => 'Email sudah digunakan, gunakan email lain.',
    ]);

    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'nip' => $request->nip,
    ]);

    return redirect()->route('admin.profile')->with('success', 'Profil berhasil diperbarui.');
}


}
