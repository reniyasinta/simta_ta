<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelompok;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class KelompokController extends Controller
{
    public function index()
    {
        $kelompok = Kelompok::with('mahasiswa')->get();
        return view('pages.mahasiswa.kelompok.index', compact('kelompok'));
    }

    public function create()
    {
        $mahasiswas = User::where('role_id', 4)->get(); // mahasiswa untuk anggota 2 & 3
        return view('pages.mahasiswa.kelompok.create', compact('mahasiswas'));
    }

   public function store(Request $request)
{
    $request->validate([
        'anggota_2_id' => 'nullable|exists:users,id|different:anggota_3_id|not_in:' . Auth::id(),
        'anggota_3_id' => 'nullable|exists:users,id|different:anggota_2_id|not_in:' . Auth::id(),
    ]);

    $authUser = Auth::user();

    // Buat data kelompok langsung lengkap dengan anggota
    $kelompok = Kelompok::create([
        'anggota_1_id' => $authUser->id,
        'anggota_2_id' => $request->anggota_2_id,
        'anggota_3_id' => $request->anggota_3_id,
    ]);

    // Set mahasiswa auth user ke kelompok
    if ($authUser->mahasiswa) {
        $authUser->mahasiswa->update([
            'id_kelompok' => $kelompok->id_kelompok,
        ]);
    }

    // Set mahasiswa anggota 2
    if ($request->filled('anggota_2_id')) {
        $anggota2 = User::find($request->anggota_2_id);
        if ($anggota2 && $anggota2->mahasiswa) {
            $anggota2->mahasiswa->update([
                'id_kelompok' => $kelompok->id_kelompok,
            ]);
        }
    }

    // Set mahasiswa anggota 3
    if ($request->filled('anggota_3_id')) {
        $anggota3 = User::find($request->anggota_3_id);
        if ($anggota3 && $anggota3->mahasiswa) {
            $anggota3->mahasiswa->update([
                'id_kelompok' => $kelompok->id_kelompok,
            ]);
        }
    }

    return redirect()->route('kelompok.index')->with('success', 'Kelompok berhasil dibuat!');
}

}
