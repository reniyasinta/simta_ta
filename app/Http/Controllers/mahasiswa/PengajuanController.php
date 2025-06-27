<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\PengajuanPembimbing;
use Illuminate\Support\Facades\Storage;
use App\Models\KuotaBimbinganDosen;
use Illuminate\Support\Facades\Mail;
use App\Mail\PengajuanDospem1Mail;


class PengajuanController extends Controller
{
    public function index()
    {
        $mahasiswa = auth()->user()->mahasiswa;

        // Jika belum ada data mahasiswa atau belum memiliki kelompok
        if (!$mahasiswa || !$mahasiswa->kelompok) {
            $pengajuan = collect(); // Kosongkan data untuk view
            $dosenList = collect();
            $error = 'Anda belum memiliki data kelompok. Bagi mahasiswa yang mengajukan TA perorangan harap tetap mendaftarkan namanya di fitur kelompok.';
            return view('pages.mahasiswa.pengajuan.index', compact('pengajuan', 'dosenList', 'error'));
        }

        // Ambil data pengajuan kelompok mahasiswa
        $pengajuan = PengajuanPembimbing::with([
            'kelompok.anggota1.mahasiswa',
            'kelompok.anggota2.mahasiswa',
            'kelompok.anggota3.mahasiswa',
            'dosen1.dosen',
            'dosen2.dosen',
        ])->where('id_kelompok', $mahasiswa->id_kelompok)->get();

        // Ambil semua dosen (tanpa batasan prodi) beserta relasi user
        $dosenList = Dosen::with('user')->get();

        // Hitung kuota bimbingan terpakai untuk masing-masing dosen
        foreach ($dosenList as $dosen) {
            $kuota = $dosen->kuota_bimbingan ?? 0;

            $jumlahSebagai1 = PengajuanPembimbing::where('id_dosen1', $dosen->user_id)
                ->where('status', 'Diterima')
                ->count();

            $jumlahSebagai2 = PengajuanPembimbing::where('id_dosen2', $dosen->user_id)
                ->where('status', 'Diterima')
                ->count();

            $dosen->kuota_terpakai = $jumlahSebagai1 + $jumlahSebagai2;
            $dosen->kuota_total = $kuota;
        }

        return view('pages.mahasiswa.pengajuan.index', compact('pengajuan', 'dosenList'));
    }



public function create()
{
    $mahasiswa = auth()->user()->mahasiswa;
    $idProdi = auth()->user()->id_prodi;

    // Mapping grup prodi (boleh saling lintas dosen)
    $groupMapping = [
        [1, 2], // TI & SIKC
        [3, 4], // Listrik TRPE
        [5, 6], // Elka TRO
        // nanti kalau mau tambah, tinggal tambah: [x, y, z]
    ];

    // Default: mahasiswa cuma bisa ambil dosen prodi sendiri
    $selectedGroup = [$idProdi];

    // Cek apakah prodi mahasiswa masuk ke salah satu grup
    foreach ($groupMapping as $group) {
        if (in_array($idProdi, $group)) {
            $selectedGroup = $group;
            break;
        }
    }

    // Ambil dosen sesuai grup yang sudah ditentukan
    $dosenList = User::where('role_id', 3)
        ->whereIn('id_prodi', $selectedGroup)
        ->get();

    // Tambahkan kuota & terpakai
    foreach ($dosenList as $dosen) {
        $jumlahSebagai1 = PengajuanPembimbing::where('id_dosen1', $dosen->id)
            ->where('status', 'Diterima')
            ->whereHas('kelompok.anggota', function ($query) use ($idProdi) {
                $query->where('id_prodi', $idProdi);
            })
            ->with('kelompok')
            ->get()
            ->sum(function ($pengajuan) {
                return $pengajuan->kelompok?->anggota->count() ?? 0;
            });

        $jumlahSebagai2 = PengajuanPembimbing::where('id_dosen2', $dosen->id)
            ->where('status', 'Diterima')
            ->whereHas('kelompok.anggota', function ($query) use ($idProdi) {
                $query->where('id_prodi', $idProdi);
            })
            ->with('kelompok')
            ->get()
            ->sum(function ($pengajuan) {
                return $pengajuan->kelompok?->anggota->count() ?? 0;
            });

        // **Ambil id_dosen dari tabel Dosen**
        $dosenModel = \App\Models\Dosen::where('user_id', $dosen->id)->first();

        $kuota = null;
        if ($dosenModel) {
            $kuota = \App\Models\KuotaBimbinganDosen::where('id_dosen', $dosenModel->id_dosen)
                ->where('id_prodi', $idProdi)
                ->first();
        }

        $dosen->kuota_total = $kuota ? $kuota->kuota_bimbingan : 0;
        $dosen->kuota_terpakai = $jumlahSebagai1 + $jumlahSebagai2;
    }

    return view('pages.mahasiswa.pengajuan.create', compact('dosenList', 'mahasiswa'));

    }

    public function store(Request $request)
    {
        $request->validate([
            'id_dosen1' => 'required|exists:users,id',
            'judul_ta' => 'required|string|max:255',
            'proposal' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $mahasiswa = auth()->user()->mahasiswa;

        if (!$mahasiswa || !$mahasiswa->kelompok) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Data kelompok tidak ditemukan.');
        }
    // Inisialisasi default kosong
    $fileName = null;

    // Hanya proses jika ada file
    if ($request->hasFile('proposal')) {
        $file = $request->file('proposal');
        $fileName =  $file->getClientOriginalName();
        $file->storeAs('proposal', $fileName, 'public');
    }

        PengajuanPembimbing::create([
            'id_kelompok' => $mahasiswa->id_kelompok,
            'id_dosen1' => $request->id_dosen1,
            'judul_ta' => $request->judul_ta,
            'proposal' => $fileName,
            'status' => 'Menunggu',
        ]);
        // 🔽 Kirim email ke dosen pembimbing 1
        $dosen = User::find($request->id_dosen1);
        if ($dosen && $dosen->email) {
            Mail::to($dosen->email)->send(new PengajuanDospem1Mail($mahasiswa, $request->judul_ta));
        }
        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil diajukan.');
    }
}
