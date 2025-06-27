<?php

namespace App\Http\Controllers\Panitia;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PengajuanPembimbing;
use Illuminate\Support\Facades\Auth;
use App\Models\Prodi;
use Illuminate\Support\Facades\Storage;
use App\Models\Mahasiswa;
use App\Models\Dosen;

class PanitiaController extends Controller
{

public function index()
{
    $user = Auth::user();
    $prodi = $user->prodi;

    // Jumlah kelompok lengkap
    $jumlahKelompokLengkap = PengajuanPembimbing::whereNotNull('id_dosen1')
        ->whereNotNull('id_dosen2')
        ->where('status', 'Diterima')
        ->whereHas('kelompok.anggota', function ($query) use ($user) {
            $query->where('id_prodi', $user->id_prodi);
        })
        ->distinct('id_kelompok')
        ->count('id_kelompok');

    // Total mahasiswa sesuai prodi panitia
    $totalMahasiswa = Mahasiswa::where('id_prodi', $user->id_prodi)->count();

    // Total dosen berdasarkan grup mapping prodi
    $groupMapping = [
        [1, 2],
        [3, 4],
        [5, 6],
    ];

    $prodiGroup = collect($groupMapping)->first(function ($group) use ($user) {
        return in_array($user->id_prodi, $group);
    });

    $totalDosen = $prodiGroup
        ? Dosen::whereIn('id_prodi', $prodiGroup)->count()
        : 0;

    return view('pages.panitia.dashboard', compact(
        'user',
        'prodi',
        'jumlahKelompokLengkap',
        'totalMahasiswa',
        'totalDosen'
    ));
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
        $user = Auth::user()->load('prodi'); // memuat relasi prodi
        return view('pages.panitia.profile', compact('user'));
    }

    // Form edit profil
    public function editProfile()
    {
        $user = Auth::user()->load('prodi');
        return view('pages.panitia.profile_edit', compact('user'));
    }

    // Update profil
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'id_prodi' => 'required|exists:prodis,id', // validasi ke tabel `prodis` kolom `id`
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->id_prodi = $request->id_prodi; // tetap disimpan walau readonly

        $user->save();

        return redirect()->route('panitia.profile')->with('success', 'Profil berhasil diperbarui.');
    }

}
