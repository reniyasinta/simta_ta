<?php
namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\PengajuanPembimbing;
use Illuminate\Support\Facades\Storage;

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

        // Jika sudah ada
        $pengajuan = PengajuanPembimbing::with([
            'kelompok.anggota1.mahasiswa',
            'kelompok.anggota2.mahasiswa',
            'kelompok.anggota3.mahasiswa',
            'dosen1',
        ])->where('id_kelompok', $mahasiswa->id_kelompok)->get();

        $dosenList = User::where('role_id', 3)->with('dosen')->get();

        return view('pages.mahasiswa.pengajuan.index', compact('pengajuan', 'dosenList'));
    }




    public function create()
    {
        $mahasiswa = auth()->user()->mahasiswa;

        // Ambil semua pengguna yang memiliki role 'dosen'
        $dosenList = User::whereHas('role', function ($query) {
            $query->where('name', 'dosen');
        })->get();

        return view('pages.mahasiswa.pengajuan.create', compact('dosenList', 'mahasiswa'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'id_dosen1' => 'required|exists:users,id',
            'judul_ta' => 'required|string|max:255',
            'proposal' => 'required|file|mimes:pdf|max:10240',
        ]);

        $mahasiswa = auth()->user()->mahasiswa;

        if (!$mahasiswa || !$mahasiswa->kelompok) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Data kelompok tidak ditemukan.');
        }

        $file = $request->file('proposal');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/proposal', $fileName);

        PengajuanPembimbing::create([
            'id_kelompok' => $mahasiswa->id_kelompok,
            'id_dosen1' => $request->id_dosen1,
            'judul_ta' => $request->judul_ta,
            'proposal' => $fileName,
            'status' => 'Menunggu',
        ]);

        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil diajukan.');
    }
}
