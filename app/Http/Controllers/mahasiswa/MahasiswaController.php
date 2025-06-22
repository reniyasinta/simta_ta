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

    // Ambil jadwal seminar/sidang/yudisium
    $jadwals = Jadwal::where(function ($query) use ($idKelompok) {
            $query->whereHas('pengajuan', function ($q) use ($idKelompok) {
                $q->where('id_kelompok', $idKelompok);
            })
            ->orWhereNull('id_ajuan'); // untuk Yudisium
        })
        ->orderByDesc('tanggal')
        ->get();

    // === Tambahkan group mapping prodi
    $groupMapping = [
        [1, 2], // TI & SIKC
        [3, 4], // Listrik & TRPE
        [5, 6], // Elka & TRO
    ];

    $prodiGroup = collect($groupMapping)->first(function ($group) use ($mahasiswa) {
        return in_array($mahasiswa->id_prodi, $group);
    });

    // Ambil dosen sesuai group prodi mahasiswa
    $dosens = Dosen::with('user', 'prodi')
        ->whereIn('id_prodi', $prodiGroup ?? [$mahasiswa->id_prodi])
        ->get();

    // Hitung kuota tiap dosen
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

        $kuota = KuotaBimbinganDosen::where('id_dosen', $dosen->id_dosen)
            ->where('id_prodi', $mahasiswa->id_prodi)
            ->first();

        $dosen->kuota_total = $kuota?->kuota_bimbingan ?? 0;
        $dosen->kuota_terpakai = $jumlahSebagai1;
    }

    return view('pages.mahasiswa.dashboard', compact('jadwals', 'dosens'));
}

    // ===== Jadwal Mahasiswa =====
    public function jadwalSeminar()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $idKelompok = $mahasiswa->id_kelompok;

        $jadwals = Jadwal::with(['penguji1', 'penguji2', 'penguji3'])
            ->where('jenis_acara', 'seminar')
            ->whereHas('pengajuan', function ($q) use ($idKelompok) {
                $q->where('id_kelompok', $idKelompok);
            })
            ->orderByDesc('tanggal')
            ->orderBy('jam_mulai')
            ->get();

        return view('pages.mahasiswa.jadwal.seminar', compact('jadwals'));
    }

    public function jadwalSidang()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $idKelompok = $mahasiswa->id_kelompok;

        $jadwals = Jadwal::with(['penguji1', 'penguji2', 'penguji3'])
            ->where('jenis_acara', 'sidang')
            ->whereHas('pengajuan', function ($q) use ($idKelompok) {
                $q->where('id_kelompok', $idKelompok);
            })
            ->orderByDesc('tanggal')
            ->orderBy('jam_mulai')
            ->get();

        return view('pages.mahasiswa.jadwal.sidang', compact('jadwals'));
    }
    public function showDosen($id)
    {
        $dosen = Dosen::with('user', 'prodi')->findOrFail($id);
        $kuota = KuotaBimbinganDosen::where('id_dosen', $id)->first();

        return view('pages.mahasiswa.dosen.show', compact('dosen', 'kuota'));
    }

}
