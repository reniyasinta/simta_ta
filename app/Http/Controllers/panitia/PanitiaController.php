<?php

namespace App\Http\Controllers\Panitia;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PengajuanPembimbing;
use Illuminate\Support\Facades\Auth;
use App\Models\Prodi;
use Illuminate\Support\Facades\Storage;



class PanitiaController extends Controller
{
public function index()
{
    $user = Auth::user();
    $prodi = $user->prodi;

    return view('pages.panitia.dashboard', compact('user', 'prodi'));
}

public function update(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . Auth::id(),
    ]);

    $user = Auth::user();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->save();

    return redirect()->route('panitia.profil')->with('success', 'Profil berhasil diperbarui.');
}

// ===== Panitia Profile =====
public function profile()
{
    $user = Auth::user()->load('prodi'); // biar nanti bisa ambil nama prodi di blade
    return view('pages.panitia.profile', compact('user'));
}

// ===== Edit Profile Panitia =====
public function editProfile()
{
    $user = Auth::user()->load('prodi');
    $prodis = Prodi::all(); // untuk dropdown pilihan prodi
    return view('pages.panitia.profile_edit', compact('user', 'prodis'));
}

// ===== Update Profile Panitia =====
public function updateProfile(Request $request)
{
    $user = Auth::user();

    // Validasi input
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'id_prodi' => 'required|exists:prodi,id_prodi',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    // Update data user
    $user->name = $request->name;
    $user->email = $request->email;
    $user->id_prodi = $request->id_prodi;

    // Handle upload foto baru
    if ($request->hasFile('foto')) {
        if ($user->foto && Storage::exists(str_replace('storage/', '', $user->foto))) {
            Storage::delete(str_replace('storage/', '', $user->foto));
        }

        $path = $request->file('foto')->store('uploads/foto_panitia', 'public');
        $user->foto = 'storage/' . $path;
    }

    $user->save();

    return redirect()->route('panitia.profile')->with('success', 'Profil berhasil diperbarui.');
}


}
