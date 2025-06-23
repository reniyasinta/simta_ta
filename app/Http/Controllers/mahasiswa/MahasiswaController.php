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

   public function updateProfile(Request $request)
{
    $user = Auth::user();
    $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();

    $request->validate([
        'nama_mhs' => 'required|string|max:255',
        'nim_mhs' => 'required|string|max:255|unique:mahasiswa,nim_mhs,' . $mahasiswa->id_mhs . ',id_mhs',
        'semester' => 'required|integer',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'no_telp' => 'required|string|max:20',
    ]);

    // Update tabel users
    $user->email = $request->email;
    $user->name = $request->nama_mhs;
    $user->save();

        // Handle upload foto baru
if ($request->hasFile('foto')) {
    // Hapus file lama kalau ada
    if ($mahasiswa->foto && Storage::disk('public')->exists('uploads/foto_mahasiswa/'.$mahasiswa->foto)) {
        Storage::disk('public')->delete('uploads/foto_mahasiswa/'.$mahasiswa->foto);
    }

    $fileName = uniqid() . '.' . $request->file('foto')->getClientOriginalExtension();
    $request->file('foto')->storeAs('uploads/foto_mahasiswa', $fileName, 'public');
    $mahasiswa->foto = $fileName; // hanya simpan nama file
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

    public function profile()
    {
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;

        return view('pages.mahasiswa.profile', compact('user', 'mahasiswa'));
    }

    public function editProfile()
    {
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;

        return view('pages.mahasiswa.profile_edit', compact('user', 'mahasiswa'));
    }
       public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();

        $request->validate([
            'nama_mhs' => 'required|string|max:255',
            'nim_mhs' => 'required|string|max:255|unique:mahasiswa,nim_mhs,' . $mahasiswa->id_mhs . ',id_mhs',
            'semester' => 'required|integer',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'no_telp' => 'required|string|max:20',
        ]);

        // Update tabel users
        $user->email = $request->email;
        $user->name = $request->nama_mhs;
        $user->save();

            // Handle upload foto baru
    if ($request->hasFile('foto')) {
        // Hapus file lama kalau ada
        if ($mahasiswa->foto && Storage::disk('public')->exists('uploads/foto_mahasiswa/'.$mahasiswa->foto)) {
            Storage::disk('public')->delete('uploads/foto_mahasiswa/'.$mahasiswa->foto);
        }

        $fileName = uniqid() . '.' . $request->file('foto')->getClientOriginalExtension();
        $request->file('foto')->storeAs('uploads/foto_mahasiswa', $fileName, 'public');
        $mahasiswa->foto = $fileName; // hanya simpan nama file
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
