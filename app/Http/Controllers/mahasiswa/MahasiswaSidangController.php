<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Sidang;
use App\Models\Jadwal;
use App\Models\PengajuanPembimbing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MahasiswaSidangController extends Controller
{
    public function draft()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $sidang = Sidang::where('id_kelompok', $mahasiswa->id_kelompok)->first();

        return view('pages.mahasiswa.sidang.draft.index', compact('sidang'));
    }

    public function createDraft()
    {
        return view('pages.mahasiswa.sidang.draft.create');
    }

    public function uploadDraft(Request $request)
    {
        $request->validate([
            'laporan_TA' => 'required|mimes:pdf|max:20480',
            'lembar_konsultasi' => 'required|mimes:pdf|max:20480',
        ]);

        $mahasiswa = Auth::user()->mahasiswa;

        // Cek pengajuan dulu
        $pengajuan = \App\Models\PengajuanPembimbing::where('id_kelompok', $mahasiswa->id_kelompok)
                        ->where('status', 'Diterima')
                        ->first();

        if (!$pengajuan) {
            return redirect()->back()->with('error', 'Pengajuan Pembimbing Diterima belum ada. Silakan cek pengajuan.');
        }

        // Cek SEMPRO
        $sempro = \App\Models\Sempro::where('id_ajuan', $pengajuan->id_ajuan)->first();

        if (!$sempro) {
            return redirect()->back()->with('error', 'Data SEMPRO tidak ditemukan. Pastikan sudah input SEMPRO.');
        }

        // Cek apakah SIDANG sudah ada
        $sidang = Sidang::where('id_kelompok', $mahasiswa->id_kelompok)->first();

        if (!$sidang) {
            // Kalau belum ada → buat baru
            $sidang = Sidang::create([
                'id_kelompok' => $mahasiswa->id_kelompok,
                'id_dosen1' => $pengajuan->id_dosen1,
                'id_dosen2' => $pengajuan->id_dosen2,
                'id_sempro' => $sempro->id_sempro,
                'status_draft_dosen1' => 'Menunggu',
                'status_draft_dosen2' => 'Menunggu',
            ]);
        }

        // Simpan file
        $laporanPath = $request->file('laporan_TA')->store('uploads/laporan_ta', 'public');
        $lembarPath = $request->file('lembar_konsultasi')->store('uploads/lembar_konsultasi', 'public');

        // Update data
        $sidang->laporan_TA = 'storage/' . $laporanPath;
        $sidang->lembar_konsultasi = 'storage/' . $lembarPath;
        $sidang->save();

        return redirect()->route('mahasiswa.sidang.draft')->with('success', 'Draft Laporan berhasil diupload.');
    }

    public function revisi()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $sidang = Sidang::where('id_kelompok', $mahasiswa->id_kelompok)->first();

        return view('pages.mahasiswa.sidang.revisi.index', compact('sidang'));
    }

    public function createRevisi()
    {
        return view('pages.mahasiswa.sidang.revisi.create');
    }

    public function uploadRevisi(Request $request)
    {
        $request->validate([
            'revisi_laporan' => 'required|mimes:pdf|max:20480',
        ]);

        $mahasiswa = Auth::user()->mahasiswa;
        $sidang = Sidang::where('id_kelompok', $mahasiswa->id_kelompok)->firstOrFail();

        $revisiPath = $request->file('revisi_laporan')->store('uploads/revisi_laporan', 'public');

        $sidang->revisi_laporan = 'storage/' . $revisiPath;
        $sidang->status_revisi_penguji_1 = 'Menunggu';
        $sidang->status_revisi_penguji_2 = 'Menunggu';
        $sidang->status_revisi_penguji_3 = 'Menunggu';
        $sidang->save();

        return redirect()->route('mahasiswa.sidang.revisi')->with('success', 'Laporan Revisi berhasil diupload.');
    }

    public function final()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $sidang = Sidang::where('id_kelompok', $mahasiswa->id_kelompok)->first();

        return view('pages.mahasiswa.sidang.final.index', compact('sidang'));
    }

    public function createFinal()
    {
        return view('pages.mahasiswa.sidang.final.create');
    }

    public function uploadFinal(Request $request)
    {
        $request->validate([
            'laporan_akhir' => 'required|mimes:pdf|max:20480',
            'lembar_konsultasi' => 'required|mimes:pdf|max:20480',
            'hasil_sidang' => 'required|mimes:pdf|max:20480',
        ]);

        $mahasiswa = Auth::user()->mahasiswa;
        $sidang = Sidang::where('id_kelompok', $mahasiswa->id_kelompok)->firstOrFail();

        $finalPath = $request->file('laporan_akhir')->store('uploads/laporan_akhir', 'public');
        $lembarPath = $request->file('lembar_konsultasi')->store('uploads/lembar_konsultasi', 'public');
        $hasilPath = $request->file('hasil_sidang')->store('uploads/hasil_sidang', 'public');

        $sidang->laporan_akhir = 'storage/' . $finalPath;
        $sidang->lembar_konsultasi = 'storage/' . $lembarPath;
        $sidang->hasil_sidang = 'storage/' . $hasilPath;
        $sidang->status_final = 'Menunggu';
        $sidang->save();

        return redirect()->route('mahasiswa.sidang.final')->with('success', 'Laporan Final berhasil diupload.');
    }
}
