<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\PengajuanPembimbing;


class PengajuanController extends Controller
{
    public function index()
    {
        $mahasiswa = Mahasiswa::where('user_id', auth()->id())->first();

        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Data mahasiswa tidak ditemukan. Silakan lengkapi profil.');
        }

        $pengajuan = PengajuanPembimbing::where('id_kelompok', $mahasiswa->id_kelompok)->get();
        $dosenList = Dosen::all();

        return view('pages.mahasiswa.pengajuan.index', compact('pengajuan', 'dosenList'));
    }


    public function create()
    {
        $mahasiswa = Mahasiswa::where('user_id', auth()->id())->first();

        // Cek apakah sudah ada pengajuan aktif
        $existing = PengajuanPembimbing::where('id_kelompok', $mahasiswa->id_kelompok)
            ->where('status', '!=', 'Ditolak')->first();

        if ($existing) {
            return redirect()->route('pengajuan.index')->with('error', 'Kelompok Anda sudah mengajukan pembimbing.');
        }
        $dosenList = Dosen::all();
        return view('pages.mahasiswa.pengajuan.create', compact( 'dosenList', 'mahasiswa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_dosen1' => 'required',
            'judul_ta' => 'required|string|max:255',
            'proposal' => 'required|file|mimes:pdf|max:10240',
        ]);

        $mahasiswa = Mahasiswa::where('user_id', auth()->id())->first();

        // Upload proposal
        $fileName = time() . '_' . $request->file('proposal')->getClientOriginalName();
        $request->file('proposal')->move(public_path('uploads/proposal'), $fileName);

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
