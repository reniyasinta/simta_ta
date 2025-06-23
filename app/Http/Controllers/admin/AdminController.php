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
    $admin = $user->admin;

    $request->validate([
        'name' => 'required|string|max:255',
        'nip' => 'required|string|max:30',
        'email' => 'required|email',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    // Update user
    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'nip' => $request->nip,
    ]);

    // Update admin
    $admin->nip = $request->nip;
    $admin->nama_admin = $request->name;

    if ($request->hasFile('foto')) {
        // Hapus foto lama
        if ($admin->foto && Storage::exists('public/uploads/foto_admin/' . $admin->foto)) {
            Storage::delete('public/uploads/foto_admin/' . $admin->foto);
        }

        // Simpan foto baru
        $file = $request->file('foto');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/uploads/foto_admin/', $filename);
        $admin->foto = $filename;
    }

    $admin->save();

    return redirect()->route('admin.profile')->with('success', 'Profil berhasil diperbarui.');
}

}
