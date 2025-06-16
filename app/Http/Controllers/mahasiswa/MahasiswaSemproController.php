<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanPembimbing;
use App\Models\Sempro;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MahasiswaSemproController extends Controller
{
    public function index()
    {
        $mahasiswa = auth()->user()->mahasiswa;

        $pengajuan = PengajuanPembimbing::with('kelompok.anggota1.mahasiswa', 'kelompok.anggota2.mahasiswa', 'kelompok.anggota3.mahasiswa')
            ->where('id_kelompok', $mahasiswa->id_kelompok)
            ->where('status', 'Diterima')
            ->first();

        $sempro = null;
        if ($pengajuan) {
            $sempro = Sempro::where('id_ajuan', $pengajuan->id_ajuan)->first();
        }

        return view('pages.mahasiswa.sempro.index', compact('pengajuan', 'sempro'));
    }

    public function uploadLaporanTa(Request $request)
    {
        $request->validate([
            'proposal_ta' => 'required|mimes:pdf|max:20480',
        ]);

        $mahasiswa = Auth::user()->mahasiswa;
        $pengajuan = PengajuanPembimbing::where('id_kelompok', $mahasiswa->id_kelompok)
            ->where('status', 'Diterima')
            ->first();

        if (!$pengajuan) {
            return redirect()->back()->with('error', 'Pengajuan belum disetujui.');
        }

        $sempro = Sempro::firstOrCreate(
            ['id_ajuan' => $pengajuan->id_ajuan],
            [
                'form_persetujuan_sempro' => '',
                'berita_acara_sempro' => '',
                'status_pengajuan' => 'Belum Diajukan'
            ]
        );

        $filePath = $request->file('proposal_ta')->store('uploads/proposal_ta', 'public');
        $sempro->proposal_ta = 'storage/' . $filePath;

        // Reset status ACC dospem
        $sempro->status_proposal_ta_dospem1= 'Menunggu';
        $sempro->status_proposal_ta_dospem2 = 'Menunggu';
        $sempro->catatan_dospem1 = null;
        $sempro->catatan_dospem2 = null;

        $sempro->save();

        return redirect()->route('mahasiswa.sempro.index')->with('success', 'Laporan TA berhasil diupload.');
    }

    public function deleteLaporanTa()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $pengajuan = PengajuanPembimbing::where('id_kelompok', $mahasiswa->id_kelompok)
            ->where('status', 'Diterima')
            ->first();

        if (!$pengajuan) {
            return redirect()->back()->with('error', 'Pengajuan belum disetujui.');
        }

        $sempro = Sempro::where('id_ajuan', $pengajuan->id_ajuan)->first();

        if ($sempro && $sempro->proposal_ta) {
            Storage::disk('public')->delete(str_replace('storage/', '', $sempro->proposal_ta));
            $sempro->proposal_ta = null;

            // Reset status ACC dospem
            $sempro->status_proposal_ta_dospem1 = 'Menunggu';
            $sempro->status_proposal_ta_dospem2 = 'Menunggu';
            $sempro->catatan_dospem1 = null;
            $sempro->catatan_dospem2 = null;

            $sempro->save();
        }

        return redirect()->route('mahasiswa.sempro.index')->with('success', 'Laporan TA berhasil dihapus.');
    }

    public function uploadForm(Request $request)
    {
        $request->validate([
            'form_persetujuan_sempro' => 'required|mimes:pdf|max:20480',
        ]);

        $mahasiswa = Auth::user()->mahasiswa;
        $pengajuan = PengajuanPembimbing::where('id_kelompok', $mahasiswa->id_kelompok)
            ->where('status', 'Diterima')
            ->first();

        if (!$pengajuan) {
            return redirect()->back()->with('error', 'Pengajuan belum disetujui.');
        }

        $sempro = Sempro::firstOrCreate(
            ['id_ajuan' => $pengajuan->id_ajuan],
            [
                'berita_acara_sempro' => '',
                'status_pengajuan' => 'Belum Diajukan'
            ]
        );

        $formPath = $request->file('form_persetujuan_sempro')->store('uploads/form_persetujuan_sempro', 'public');
        $sempro->form_persetujuan_sempro = 'storage/' . $formPath;

        $sempro->status_pengajuan = 'Belum Diajukan';

        $sempro->save();

        return redirect()->route('mahasiswa.sempro.index')->with('success', 'Form Persetujuan Sempro berhasil diupload.');
    }

    public function deleteForm()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $pengajuan = PengajuanPembimbing::where('id_kelompok', $mahasiswa->id_kelompok)
            ->where('status', 'Diterima')
            ->first();

        if (!$pengajuan) {
            return redirect()->back()->with('error', 'Pengajuan belum disetujui.');
        }

        $sempro = Sempro::where('id_ajuan', $pengajuan->id_ajuan)->first();

        if ($sempro && $sempro->form_persetujuan_sempro) {
            Storage::disk('public')->delete(str_replace('storage/', '', $sempro->form_persetujuan_sempro));
            $sempro->form_persetujuan_sempro = null;
            $sempro->status_pengajuan = 'Belum Diajukan';
            $sempro->save();
        }

        return redirect()->route('mahasiswa.sempro.index')->with('success', 'Form Persetujuan Sempro berhasil dihapus.');
    }

    public function uploadHasil(Request $request)
    {
        $request->validate([
            'berita_acara_sempro' => 'required|mimes:pdf|max:20480',
        ]);

        $mahasiswa = Auth::user()->mahasiswa;
        $pengajuan = PengajuanPembimbing::where('id_kelompok', $mahasiswa->id_kelompok)
            ->where('status', 'Diterima')
            ->first();

        if (!$pengajuan) {
            return redirect()->back()->with('error', 'Pengajuan belum disetujui.');
        }

        $sempro = Sempro::firstOrCreate(
            ['id_ajuan' => $pengajuan->id_ajuan]
        );

        $hasilPath = $request->file('berita_acara_sempro')->store('uploads/berita_acara_sempro', 'public');
        $sempro->berita_acara_sempro = 'storage/' . $hasilPath;

        $sempro->save();

        return redirect()->route('mahasiswa.sempro.index')->with('success', 'Berita Acara berhasil diupload.');
    }

    public function deleteHasil()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $pengajuan = PengajuanPembimbing::where('id_kelompok', $mahasiswa->id_kelompok)
            ->where('status', 'Diterima')
            ->first();

        if (!$pengajuan) {
            return redirect()->back()->with('error', 'Pengajuan belum disetujui.');
        }

        $sempro = Sempro::where('id_ajuan', $pengajuan->id_ajuan)->first();

        if ($sempro && $sempro->berita_acara_sempro) {
            Storage::disk('public')->delete(str_replace('storage/', '', $sempro->berita_acara_sempro));
            $sempro->berita_acara_sempro = null;
            $sempro->save();
        }

        return redirect()->route('mahasiswa.sempro.index')->with('success', 'Berita Acara berhasil dihapus.');
    }

    public function ajukan()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $pengajuan = PengajuanPembimbing::where('id_kelompok', $mahasiswa->id_kelompok)
            ->where('status', 'Diterima')
            ->first();

        if (!$pengajuan) {
            return back()->with('error', 'Pengajuan belum disetujui.');
        }

        $sempro = Sempro::where('id_ajuan', $pengajuan->id_ajuan)->first();

        if (!$sempro || !$sempro->form_persetujuan_sempro) {
            return back()->with('error', 'Form Persetujuan belum diupload.');
        }

        $sempro->status_pengajuan = 'Diajukan';
        $sempro->save();

        return back()->with('success', 'Form Persetujuan berhasil diajukan ke Panitia.');
    }
}
