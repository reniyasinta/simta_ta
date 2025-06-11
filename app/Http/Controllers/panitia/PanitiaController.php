<?php

namespace App\Http\Controllers\Panitia;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PengajuanPembimbing;

class PanitiaController extends Controller
{
public function index()
{
    $user = Auth::user();
    $prodi = $user->prodi;

    return view('pages.panitia.profil', compact('user', 'prodi'));
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

}
