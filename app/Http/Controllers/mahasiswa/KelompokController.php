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
        $mahasiswa = auth()->user()->mahasiswa;

        // Jika mahasiswa belum memiliki kelompok, tampilkan view kosong
        if (!$mahasiswa || !$mahasiswa->id_kelompok) {
            return view('pages.mahasiswa.kelompok.index', [
                'kelompok' => [],
            ]);
        }

        // Ambil kelompok milik mahasiswa ini
        $kelompok = Kelompok::with('mahasiswa')
            ->where('id_kelompok', $mahasiswa->id_kelompok)
            ->get();

        return view('pages.mahasiswa.kelompok.index', compact('kelompok'));
    }

    public function create()
    {
        $mahasiswa = auth()->user()->mahasiswa;

        if ($mahasiswa && $mahasiswa->id_kelompok) {
            return redirect()->route('kelompok.index')->with('error', 'Anda sudah tergabung dalam kelompok.');
        }

        // Ambil mahasiswa lain yang satu prodi, belum punya kelompok, dan bukan diri sendiri
        $mahasiswas = User::whereHas('mahasiswa', function ($query) use ($mahasiswa) {
            $query->where('id_prodi', $mahasiswa->id_prodi)
                ->whereNull('id_kelompok'); // Belum tergabung kelompok
        })
        ->where('role_id', 4) // Role Mahasiswa
        ->where('id', '!=', auth()->id()) // Bukan diri sendiri
        ->get();

        return view('pages.mahasiswa.kelompok.create', compact('mahasiswas'));
    }

   public function store(Request $request)
{
    $request->validate([
        'anggota_2_id' => 'nullable|exists:users,id|different:anggota_3_id|not_in:' . Auth::id(),
        'anggota_3_id' => 'nullable|exists:users,id|different:anggota_2_id|not_in:' . Auth::id(),
    ]);

    $authUser = Auth::user();

    $kelompok = Kelompok::create([
        'anggota_1_id' => $authUser->id,
        'anggota_2_id' => $request->anggota_2_id,
        'anggota_3_id' => $request->anggota_3_id,
    ]);

    // Set mahasiswa utama
    if ($authUser->mahasiswa) {
        $authUser->mahasiswa->update([
            'id_kelompok' => $kelompok->id_kelompok,
        ]);
    }

    // Set anggota 2
    if ($request->filled('anggota_2_id')) {
        $anggota2 = User::find($request->anggota_2_id);
        if ($anggota2 && $anggota2->mahasiswa) {
            $anggota2->mahasiswa->update([
                'id_kelompok' => $kelompok->id_kelompok,
            ]);
        }
    }

    // Set anggota 3
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
