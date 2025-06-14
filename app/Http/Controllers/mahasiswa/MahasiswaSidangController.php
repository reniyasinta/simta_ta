<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Sidang;
use App\Models\Jadwal;
use App\Models\TAConfig;
use App\Models\PengajuanPembimbing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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
        return redirect()->back()->with('error', 'Data Sempro tidak ditemukan. Pastikan sudah input Sempro.');
    }

    // Cek apakah SIDANG sudah ada
    $sidang = Sidang::where('id_kelompok', $mahasiswa->id_kelompok)->first();

    if (!$sidang) {
        $sidang = Sidang::create([
            'id_kelompok' => $mahasiswa->id_kelompok,
            'id_dosen1' => $pengajuan->id_dosen1,
            'id_dosen2' => $pengajuan->id_dosen2,
            'id_sempro' => $sempro->id_sempro,
            'status_draft_dosen1' => 'Menunggu',
            'status_draft_dosen2' => 'Menunggu',
        ]);
    }

    // Simpan file ke storage
    $laporanPath = $request->file('laporan_TA')->store('uploads/laporan_ta', 'public');
    $lembarPath = $request->file('lembar_konsultasi')->store('uploads/lembar_konsultasi', 'public');

    // Simpan ke tabel sidang_uploads (bukan ke tabel sidang lagi)
    $sidang->uploads()->create([
        'jenis_upload' => 'draft',
        'file_path' => 'storage/' . $laporanPath,
        'file_path_2' => 'storage/' . $lembarPath,
        'uploaded_at' => now(),
    ]);

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

    // Simpan ke tabel sidang_uploads (bukan ke sidang)
    $sidang->uploads()->create([
        'jenis_upload' => 'revisi',
        'file_path' => 'storage/' . $revisiPath,
        'uploaded_at' => now(),
    ]);

    // Update status revisi penguji tetap boleh
    $sidang->update([
        'status_revisi_penguji_1' => 'Menunggu',
        'status_revisi_penguji_2' => 'Menunggu',
        'status_revisi_penguji_3' => 'Menunggu',
    ]);

    return redirect()->route('mahasiswa.sidang.revisi')->with('success', 'Laporan Revisi berhasil diupload.');
}

     public function final()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $sidang = Sidang::where('id_kelompok', $mahasiswa->id_kelompok)->first();

        $prodiId = $mahasiswa->id_prodi;
        $linkConfig = TAConfig::where('id_prodi', $prodiId)
            ->where('nama_konfigurasi', 'link_drive_proyek_zip')
            ->first();

        $link_drive_proyek = $linkConfig ? $linkConfig->config_value : null;

        return view('pages.mahasiswa.sidang.final.index', compact('sidang', 'link_drive_proyek'));
    }

    public function createFinal()
    {
        return view('pages.mahasiswa.sidang.final.create');
    }


public function uploadFinal(Request $request)
{
    try {
        $request->validate([
            'laporan_akhir_pdf' => 'required|mimes:pdf|max:20480',
            'laporan_akhir_word' => 'required|mimes:doc,docx|max:20480',
            'berita_acara' => 'required|mimes:pdf|max:20480',
            'buku_manual' => 'required|mimes:doc,docx|max:20480',
            'halaman_pengesahan' => 'required|mimes:pdf,doc,docx|max:20480',
        ]);

        $mahasiswa = Auth::user()->mahasiswa;
        $sidang = Sidang::where('id_kelompok', $mahasiswa->id_kelompok)->firstOrFail();

        $errors = [];

        // 1. Cek apakah revisi_laporan null
        if (is_null($sidang->revisi_laporan)) {
            $errors['revisi_laporan'] = 'File revisi laporan belum tersedia.';
        }

        // 2. Cek status revisi penguji (tidak boleh ada yang menunggu atau revisi)
        if (is_null($sidang->revisi_laporan)) {
            $errors['revisi_laporan'] = 'File revisi laporan belum tersedia.';
        }

        // === 2. Validasi status revisi penguji satu per satu ===
        if (in_array($sidang->status_revisi_penguji_1, ['Menunggu', 'Revisi'])) {
            $errors['status_revisi_penguji_1'] = 'Status revisi dari Penguji 1 belum selesai.';
        }

        if (in_array($sidang->status_revisi_penguji_2, ['Menunggu', 'Revisi'])) {
            $errors['status_revisi_penguji_2'] = 'Status revisi dari Penguji 2 belum selesai.';
        }

        if (in_array($sidang->status_revisi_penguji_3, ['Menunggu', 'Revisi'])) {
            $errors['status_revisi_penguji_3'] = 'Status revisi dari Penguji 3 belum selesai.';
        }

        // 3. Cek apakah mahasiswa punya jadwal sidang
        $adaJadwal = \App\Models\Jadwal::where('id_ajuan', $sidang->id_sempro)->exists();
        if (! $adaJadwal) {
            $errors['jadwal'] = 'Jadwal sidang belum tersedia untuk mahasiswa ini.';
        }

        // Jika ada error, lempar validation
        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }

        // Upload file
        $filePaths = [];
        foreach ([
            'laporan_akhir_pdf',
            'laporan_akhir_word',
            'berita_acara',
            'buku_manual',
            'halaman_pengesahan',
        ] as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $originalName = $file->getClientOriginalName();
                $path = $file->storeAs("uploads/{$field}", $originalName, 'public');
                $filePaths[$field] = 'storage/' . $path;
            }
        }

        $sidang->update($filePaths);

        return redirect()->route('mahasiswa.sidang.final')->with('success', 'Laporan Final berhasil diupload.');

    } catch (ValidationException $e) {
        return back()->withErrors($e->validator)->withInput();

    } catch (ModelNotFoundException $e) {
        return back()->withErrors(['error' => 'Pra sidang belum selesai'])->withInput();

    } catch (FileException $e) {
        return back()->withErrors(['error' => 'Gagal menyimpan file.'])->withInput();

    } catch (\Exception $e) {
        \Log::error('Upload final gagal: ' . $e->getMessage());
        return back()->withErrors(['error' => 'Terjadi kesalahan tak terduga.'])->withInput();
    }
}
public function deleteFinal($jenis)
{
    $allowedFields = [
        'laporan_akhir_pdf',
        'laporan_akhir_word',
        'lembar_konsultasi',
        'berita_acara',
        'buku_manual',
        'halaman_pengesahan',
    ];

    if (!in_array($jenis, $allowedFields)) {
        return redirect()->back()->with('error', 'Jenis file tidak dikenali.');
    }

    $mahasiswa = Auth::user()->mahasiswa;
    $sidang = Sidang::where('id_kelompok', $mahasiswa->id_kelompok)->firstOrFail();

    $filePath = $sidang->$jenis;

    // Hapus file dari disk
    if ($filePath && \Storage::disk('public')->exists(str_replace('storage/', '', $filePath))) {
        \Storage::disk('public')->delete(str_replace('storage/', '', $filePath));
    }

    // Set kolom ke null di database
    $sidang->$jenis = null;
    $sidang->save();

    return redirect()->back()->with('success', 'File berhasil dihapus.');
}
public function editFinal($jenis)
{
    $allowedFields = [
        'laporan_akhir_pdf',
        'laporan_akhir_word',
        'lembar_konsultasi',
        'berita_acara',
        'buku_manual',
        'halaman_pengesahan',
    ];

    if (!in_array($jenis, $allowedFields)) {
        abort(404);
    }

    $label = ucwords(str_replace('_', ' ', $jenis));
    return view('pages.mahasiswa.sidang.final.edit', compact('jenis', 'label'));
}

public function updateFinal(Request $request, $jenis)
{
    $allowedFields = [
        'laporan_akhir_pdf' => 'pdf',
        'laporan_akhir_word' => 'doc,docx',
        'lembar_konsultasi' => 'pdf',
        'berita_acara' => 'pdf',
        'buku_manual' => 'doc,docx',
        'halaman_pengesahan' => 'pdf,doc,docx',
    ];

    if (!array_key_exists($jenis, $allowedFields)) {
        abort(404);
    }

    $request->validate([
        'file' => 'required|mimes:' . $allowedFields[$jenis] . '|max:20480',
    ]);

    $mahasiswa = Auth::user()->mahasiswa;
    $sidang = Sidang::where('id_kelompok', $mahasiswa->id_kelompok)->firstOrFail();

    // Simpan file
    $file = $request->file('file');
    $path = $file->storeAs("uploads/$jenis", $file->getClientOriginalName(), 'public');

    $sidang->$jenis = 'storage/' . $path;
    $sidang->save();

    return redirect()->route('mahasiswa.sidang.final')->with('success', 'File berhasil diupdate.');
}

}
