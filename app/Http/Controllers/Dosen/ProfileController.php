<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Dosen;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $dosen = Dosen::with('prodi')->where('user_id', $user->id)->firstOrFail();

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

        // Update email user
        $user->email = $request->email;

        // Optional: jika kamu punya kolom 'name' di tabel users dan ingin sync nama dosen
        if (property_exists($user, 'name')) {
            $user->name = $request->nama_dosen;
        }

        $user->save();

        // Handle upload foto
        if ($request->hasFile('foto')) {
            if ($dosen->foto && file_exists(public_path('uploads/foto_dosen/' . $dosen->foto))) {
                unlink(public_path('uploads/foto_dosen/' . $dosen->foto));
            }

            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/foto_dosen'), $filename);
            $dosen->foto = $filename;
        }

        // Update data dosen
        $dosen->nama_dosen = $request->nama_dosen;
        $dosen->nip_dosen = $request->nip_dosen;
        $dosen->keahlian = $request->keahlian;
        $dosen->no_telp = $request->no_telp;
        $dosen->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }

        public function edit()
    {
        $user = Auth::user();
        $dosen = Dosen::with('prodi')->where('user_id', $user->id)->firstOrFail();
        return view('pages.dosen.profile_edit', compact('user', 'dosen'));
    }

}
