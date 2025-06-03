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

        // cari sempro, kalau belum ada → buat
        $sempro = Sempro::firstOrCreate(
            ['id_ajuan' => $pengajuan->id_ajuan],
            ['form_persetujuan_sempro' => '', 'hasil_sempro' => '']
        );

        // upload file
        $formPath = $request->file('form_persetujuan_sempro')->store('uploads/form_persetujuan_sempro', 'public');
        $sempro->form_persetujuan_sempro = 'storage/' . $formPath;
        $sempro->save();

        return redirect()->route('mahasiswa.sempro.index')->with('success', 'Form Persetujuan SEMPRO berhasil diupload.');
    }

    public function uploadHasil(Request $request)
    {
        $request->validate([
            'hasil_sempro' => 'required|mimes:pdf|max:20480',
        ]);

        $mahasiswa = Auth::user()->mahasiswa;
        $pengajuan = PengajuanPembimbing::where('id_kelompok', $mahasiswa->id_kelompok)
            ->where('status', 'Diterima')
            ->first();

        if (!$pengajuan) {
            return redirect()->back()->with('error', 'Pengajuan belum disetujui.');
        }

        // cari sempro, kalau belum ada → buat
        $sempro = Sempro::firstOrCreate(
            ['id_ajuan' => $pengajuan->id_ajuan],
            ['form_persetujuan_sempro' => '', 'hasil_sempro' => '']
        );

        // upload file
        $hasilPath = $request->file('hasil_sempro')->store('uploads/hasil_sempro', 'public');
        $sempro->hasil_sempro = 'storage/' . $hasilPath;
        $sempro->save();

        return redirect()->route('mahasiswa.sempro.index')->with('success', 'Hasil SEMPRO berhasil diupload.');
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
        $sempro->save();
    }

    return redirect()->route('mahasiswa.sempro.index')->with('success', 'Form Persetujuan SEMPRO berhasil dihapus.');
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

    if ($sempro && $sempro->hasil_sempro) {
        Storage::disk('public')->delete(str_replace('storage/', '', $sempro->hasil_sempro));
        $sempro->hasil_sempro = null;
        $sempro->save();
    }

    return redirect()->route('mahasiswa.sempro.index')->with('success', 'Hasil SEMPRO berhasil dihapus.');
}

}
