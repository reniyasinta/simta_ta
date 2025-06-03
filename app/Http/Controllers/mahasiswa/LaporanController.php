<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Sidang;
use App\Models\PengajuanPembimbing; // ini kita pakai

class LaporanController extends Controller
{
    public function laporanTA()
    {
        $sidang = Sidang::where('id_mhs', Auth::user()->mahasiswa->id_mhs)->first();

        return view('pages.mahasiswa.laporan-ta.index', compact('sidang'));
    }

public function uploadLaporanTA(Request $request)
{
    $request->validate([
        'laporan_TA' => 'required|mimes:pdf|max:20480',
    ]);

    // Ambil id_kelompok mahasiswa
    $id_kelompok = Auth::user()->mahasiswa->id_kelompok;

    // Ambil pengajuan pembimbing
    $pengajuan = PengajuanPembimbing::where('id_kelompok', $id_kelompok)
        ->where('status', 'Diterima')
        ->first();

    // Cek apakah pengajuan sudah ada
    if (!$pengajuan) {
        return redirect()->back()->with('error', 'Pengajuan pembimbing belum disetujui.');
    }

    // Cek apakah mahasiswa sudah punya SEMPRO
    $sempro = \App\Models\Sempro::where('id_ajuan', $pengajuan->id_ajuan)->first();

    if (!$sempro) {
        return redirect()->back()->with('error', 'Anda belum mengikuti SEMPRO. Silakan daftar SEMPRO terlebih dahulu.');
    }

    // Ambil id_sempro dan id_dosen1
    $id_sempro = $sempro->id_Sempro;
    $id_dosen1 = $pengajuan->id_dosen1;

    // Buat data sidang jika belum ada
    $sidang = Sidang::firstOrCreate(
        ['id_mhs' => Auth::user()->mahasiswa->id_mhs],
        [
            'id_dosen' => $id_dosen1,
            'id_sempro' => $id_sempro,
            'status_draft' => 'Menunggu',
            'status_revisi' => 'Menunggu',
            'status_final' => 'Menunggu',
        ]
    );

    // Proses upload
    if ($request->hasFile('laporan_TA')) {
        // Hapus file lama jika ada
        if ($sidang->laporan_TA && Storage::exists(str_replace('storage/', '', $sidang->laporan_TA))) {
            Storage::delete(str_replace('storage/', '', $sidang->laporan_TA));
        }

        // Upload file baru
        $path = $request->file('laporan_TA')->store('uploads/laporan_ta', 'public');
        $sidang->laporan_TA = 'storage/' . $path;
        $sidang->status_draft = 'Menunggu';
        $sidang->catatan_dosen = null;
        $sidang->save();
    }

    return redirect()->route('mahasiswa.laporan-ta')->with('success', 'Laporan TA berhasil diupload.');
}

    public function revisiLaporan()
    {
        $sidang = Sidang::where('id_mhs', Auth::user()->mahasiswa->id_mhs)->first();

        return view('pages.mahasiswa.revisi-laporan.index', compact('sidang'));
    }

    public function uploadRevisiLaporan(Request $request)
{
    $request->validate([
        'revisi_laporan' => 'required|mimes:pdf|max:20480',
    ]);

    // Ambil id_kelompok mahasiswa
    $id_kelompok = Auth::user()->mahasiswa->id_kelompok;

    // Ambil pengajuan pembimbing
    $pengajuan = PengajuanPembimbing::where('id_kelompok', $id_kelompok)
        ->where('status', 'Diterima')
        ->first();

    // Cek apakah pengajuan sudah ada
    if (!$pengajuan) {
        return redirect()->back()->with('error', 'Pengajuan pembimbing belum disetujui.');
    }

    // Ambil id_sempro dan id_dosen
    $id_sempro = $pengajuan?->id_sempro ?? null;
    $id_dosen1 = $pengajuan?->id_dosen1 ?? null;

    // Cek apakah id_sempro sudah ada
    if ($id_sempro == null) {
        return redirect()->back()->with('error', 'Anda belum mengikuti SEMPRO. Silakan daftar SEMPRO terlebih dahulu.');
    }

    // Buat data sidang jika belum ada
    $sidang = Sidang::firstOrCreate(
        ['id_mhs' => Auth::user()->mahasiswa->id_mhs],
        [
            'id_dosen' => $id_dosen1,
            'id_sempro' => $id_sempro,
            'status_draft' => 'Menunggu',
            'status_revisi' => 'Menunggu',
            'status_final' => 'Menunggu',
        ]
    );

    // Proses upload
    if ($request->hasFile('revisi_laporan')) {
        // Hapus file lama jika ada
        if ($sidang->revisi_laporan && Storage::exists(str_replace('storage/', '', $sidang->revisi_laporan))) {
            Storage::delete(str_replace('storage/', '', $sidang->revisi_laporan));
        }

        // Upload file baru
        $path = $request->file('revisi_laporan')->store('uploads/revisi_laporan', 'public');
        $sidang->revisi_laporan = 'storage/' . $path;
        $sidang->status_revisi = 'Menunggu';
        $sidang->catatan_dosen = null;
        $sidang->save();
    }

    return redirect()->route('mahasiswa.revisi-laporan')->with('success', 'Revisi laporan berhasil diupload.');
}
}
