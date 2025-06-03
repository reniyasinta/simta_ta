<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\PengajuanPembimbing;
use Illuminate\Support\Facades\Storage;
use App\Models\KuotaBimbinganDosen;

class MahasiswaController extends Controller
{
    // ===== Mahasiswa Dashboard =====
    public function index()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            return redirect()->back()->withErrors(['Anda belum terdaftar sebagai mahasiswa.']);
        }

        $idKelompok = $mahasiswa->id_kelompok;

        // Ambil jadwal untuk kelompok mahasiswa ini
        $jadwals = Jadwal::where(function ($query) use ($idKelompok) {
                $query->whereHas('pengajuan', function ($q) use ($idKelompok) {
                    $q->where('id_kelompok', $idKelompok);
                })
                ->orWhereNull('id_ajuan'); // untuk jadwal Yudisium
            })
            ->orderByDesc('tanggal')
            ->get();

        // Ambil semua dosen + hitung kuota bimbingan per prodi
        $dosens = Dosen::with('user', 'prodi')->get();

        foreach ($dosens as $dosen) {
            $jumlahSebagai1 = PengajuanPembimbing::where('id_dosen1', $dosen->user_id)
                ->where('status', 'Diterima')
                ->whereHas('kelompok.anggota', function ($query) use ($mahasiswa) {
                    $query->where('id_prodi', $mahasiswa->id_prodi);
                })
                ->with('kelompok')
                ->get()
                ->sum(function ($pengajuan) {
                    return $pengajuan->kelompok?->anggota->count() ?? 0;
                });

            $jumlahSebagai2 = PengajuanPembimbing::where('id_dosen2', $dosen->user_id)
                ->where('status', 'Diterima')
                ->whereHas('kelompok.anggota', function ($query) use ($mahasiswa) {
                    $query->where('id_prodi', $mahasiswa->id_prodi);
                })
                ->with('kelompok')
                ->get()
                ->sum(function ($pengajuan) {
                    return $pengajuan->kelompok?->anggota->count() ?? 0;
                });

            // Ambil kuota per prodi
            $kuota = KuotaBimbinganDosen::where('id_dosen', $dosen->id_dosen)
                ->where('id_prodi', $mahasiswa->id_prodi)
                ->first();

            $dosen->kuota_total = $kuota ? $kuota->kuota_bimbingan : 0;
            $dosen->kuota_terpakai = $jumlahSebagai1 + $jumlahSebagai2;
        }

        return view('pages.mahasiswa.dashboard', compact('jadwals', 'dosens'));
    }

    // ===== Mahasiswa Profile =====
    public function profile()
    {
        $mahasiswa = Mahasiswa::with(['user.prodi'])
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('pages.mahasiswa.profile', compact('mahasiswa'));
    }

    // ===== Edit Profile Mahasiswa =====
    public function editProfile()
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::with('prodi')->where('user_id', $user->id)->firstOrFail();

        return view('pages.mahasiswa.profile_edit', compact('user', 'mahasiswa'));
    }

    // ===== Update Profile Mahasiswa =====
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();

        // Validasi input
        $request->validate([
            'nama_mhs' => 'required|string|max:255',
            'nim_mhs' => 'required|string|max:255|unique:users,nim,' . $user->id,
            'semester' => 'required|integer',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'no_telp' => 'required|string|max:20',
        ]);

        // Sinkron ke tabel users
        $user->email = $request->email;
        $user->name = $request->nama_mhs;
        $user->nim = $request->nim_mhs;
        $user->save();

        // Handle upload foto baru
        if ($request->hasFile('foto')) {
            if ($mahasiswa->foto && Storage::exists(str_replace('storage/', '', $mahasiswa->foto))) {
                Storage::delete(str_replace('storage/', '', $mahasiswa->foto));
            }

            $path = $request->file('foto')->store('uploads/foto_mahasiswa', 'public');
            $mahasiswa->foto = 'storage/' . $path;
        }

        // Update tabel mahasiswa
        $mahasiswa->nama_mhs = $request->nama_mhs;
        $mahasiswa->nim_mhs = $request->nim_mhs;
        $mahasiswa->semester = $request->semester;
        $mahasiswa->id_prodi = $user->id_prodi;
        $mahasiswa->no_telp = $request->no_telp;
        $mahasiswa->save();

        return redirect()->route('mahasiswa.profile')->with('success', 'Profil berhasil diperbarui.');
    }

    // ===== Jadwal Mahasiswa =====
    public function jadwal()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $idKelompok = $mahasiswa->id_kelompok;

        // Ambil semua jadwal yang terkait dengan kelompok mahasiswa
        $jadwals = Jadwal::with(['penguji1', 'penguji2', 'penguji3'])
            ->where(function ($query) use ($idKelompok) {
                $query->whereHas('pengajuan', function ($q) use ($idKelompok) {
                    $q->where('id_kelompok', $idKelompok);
                })
                ->orWhereNull('id_ajuan'); // untuk jadwal Yudisium
            })
            ->orderByDesc('tanggal')
            ->orderBy('jam_mulai')
            ->get();

        return view('pages.mahasiswa.jadwal.index', compact('jadwals'));
    }
}
