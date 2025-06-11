<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Surat;
use App\Models\PengajuanPembimbing;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


class SuratController extends Controller
{
    public function index(Request $request)
    {
        $mahasiswa = Mahasiswa::where('user_id', auth()->id())->first();

        $query = Surat::where('id_mhs', $mahasiswa->id_mhs);

        if ($request->filled('perihal')) {
            $query->where('perihal', $request->perihal);
        }

        $suratList = $query->latest()->get();

        return view('pages.mahasiswa.surat.index', compact('suratList'));
    }

    public function create()
    {
        $mahasiswa = Mahasiswa::where('user_id', Auth::id())->firstOrFail();

        $pengajuan = PengajuanPembimbing::whereHas('kelompok', function ($q) use ($mahasiswa) {
            $q->whereHas('anggota', function ($qq) use ($mahasiswa) {
                $qq->where('id_mhs', $mahasiswa->id_mhs);
            });
        })->where('status', 'Diterima')->latest()->first();

        // Validasi dospem 1 dan 2 wajib lengkap
        if (!$pengajuan || !$pengajuan->id_dosen1 || !$pengajuan->id_dosen2) {
            return redirect()->route('mahasiswa.surat.index')->with('error', 'Dosen Pembimbing 1 dan/atau 2 belum lengkap.');
        }

        // ✅ TAMBAHKAN INI
        $dospem1 = $pengajuan->dosen1->dosen->nama_dosen ?? '-';

        return view('pages.mahasiswa.surat.create', compact('mahasiswa', 'dospem1'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'judul_ta' => 'required',
            'perihal' => 'required',
            'tujuan' => 'required',
        ]);

        $mahasiswa = Mahasiswa::where('user_id', auth()->id())->first();


        // Ambil dospem dari pengajuan
        $pengajuan = PengajuanPembimbing::whereHas('kelompok', function ($q) use ($mahasiswa) {
            $q->whereHas('anggota', function ($qq) use ($mahasiswa) {
                $qq->where('id_mhs', $mahasiswa->id_mhs);
            });
        })->where('status', 'Diterima')->latest()->first();

        $dospem1 = $pengajuan->dosen1->dosen->nama_dosen ?? 'Belum Ditentukan';

        Surat::create([
            'id_mhs' => $mahasiswa->id_mhs,
            'judul_ta' => $request->judul_ta,
            'perihal' => $request->perihal,
            'tujuan' => $request->tujuan,
            'status' => 'menunggu',
        ]);

        return redirect()->route('mahasiswa.surat.index')->with('success', 'Pengajuan surat berhasil.');
    }

    public function download($id)
    {
        $surat = Surat::findOrFail($id);

        if (!$surat->file_surat || !Storage::disk('public')->exists($surat->file_surat)) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($surat->file_surat);
    }
}
