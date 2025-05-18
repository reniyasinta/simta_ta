<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Dosen;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $dosen = Dosen::with('prodi')->where('user_id', $user->id)->first();

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
        $dosen = Dosen::where('user_id', $user->id)->first();

        $user->email = $request->email;
        $user->save();

        // Handle upload foto
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/foto_dosen'), $filename);
            $dosen->foto = $filename;
        }

        $dosen->update([
            'nama_dosen' => $request->nama_dosen,
            'nip_dosen' => $request->nip_dosen,
            'keahlian' => $request->keahlian,
            'no_telp' => $request->no_telp,
        ]);

        $dosen->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
}
