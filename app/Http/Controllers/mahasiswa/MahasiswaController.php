<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Storage;


class MahasiswaController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // pastikan user adalah instance dari App\Models\User
        $mahasiswa = $user->mahasiswa; // relasi dari model User ke Mahasiswa

        if (!$mahasiswa) {
            return redirect()->back()->withErrors(['Anda belum terdaftar sebagai mahasiswa.']);
        }

        $jadwals = Jadwal::where('id_mhs', $mahasiswa->id_mhs)->get();
        $dosens = Dosen::with('user', 'prodi')->get();

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
            'nim_mhs' => 'required|string|max:255',
            'semester' => 'required|integer',
            'email' => 'required|email',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:20480',
        ]);

        // Update user email
        $user->email = $request->email;
        $user->save();

        // Handle foto
        if ($request->hasFile('foto')) {
            if ($mahasiswa->foto && Storage::exists($mahasiswa->foto)) {
                Storage::delete($mahasiswa->foto);
            }
            $path = $request->file('foto')->store('uploads/foto_mahasiswa', 'public');
            $mahasiswa->foto = 'storage/' . $path;
        }

        // Update data mahasiswa
        $mahasiswa->nama_mhs = $request->nama_mhs;
        $mahasiswa->nim_mhs = $request->nim_mhs;
        $mahasiswa->semester = $request->semester;
        $mahasiswa->id_prodi = $user->id_prodi; // <-- otomatis ikut dari user
        $mahasiswa->save();

        return redirect()->route('mahasiswa.profile')->with('success', 'Profil berhasil diperbarui.');
    }


}
