<?php
namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Sidang;

class LaporanAkhirController extends Controller
{
    public function laporanAkhir()
    {
        $sidang = Sidang::where('id_mhs', Auth::user()->mahasiswa->id_mhs)->first();

        return view('pages.mahasiswa.laporan-akhir.index', compact('sidang'));
    }

    public function uploadLaporanAkhir(Request $request)
    {
        $request->validate([
            'laporan_akhir' => 'required|mimes:pdf|max:20480',
        ]);

        $sidang = Sidang::where('id_mhs', Auth::user()->mahasiswa->id_mhs)->first();

        if (!$sidang) {
            return redirect()->back()->with('error', 'Data sidang tidak ditemukan.');
        }

        if ($request->hasFile('laporan_akhir')) {
            if ($sidang->laporan_akhir && Storage::exists(str_replace('storage/', '', $sidang->laporan_akhir))) {
                Storage::delete(str_replace('storage/', '', $sidang->laporan_akhir));
            }

            $path = $request->file('laporan_akhir')->store('uploads/laporan_akhir', 'public');
            $sidang->laporan_akhir = 'storage/' . $path;
            $sidang->status_final = 'Menunggu';
            $sidang->save();
        }

        return redirect()->route('mahasiswa.laporan-akhir')->with('success', 'Laporan akhir berhasil diupload.');
    }
}

