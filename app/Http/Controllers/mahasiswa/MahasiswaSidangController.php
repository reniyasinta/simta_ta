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
    $mahasiswa = Auth::user()->mahasiswa;

    if (!$mahasiswa || !$mahasiswa->id_kelompok) {
        return redirect()->back()->with('error', 'Anda belum memiliki kelompok.');
    }

    $pengajuan = \App\Models\PengajuanPembimbing::where('id_kelompok', $mahasiswa->id_kelompok)
        ->where('status', 'Diterima')
        ->first();

    if (!$pengajuan) {
        return redirect()->back()->with('error', 'Anda belum memiliki dosen pembimbing yang disetujui.');
    }

    $sempro = \App\Models\Sempro::where('id_ajuan', $pengajuan->id_ajuan)->first();
    if (!$sempro) {
        return redirect()->back()->with('error', 'Data seminar proposal belum ditemukan.');
    }

    // Dapatkan atau buat sidang
    $sidang = Sidang::firstOrNew(['id_kelompok' => $mahasiswa->id_kelompok]);
    $sidang->id_dosen1 = $pengajuan->id_dosen1;
    $sidang->id_dosen2 = $pengajuan->id_dosen2;
    $sidang->id_sempro = $sempro->id_sempro;

    // ===== Validasi & Simpan File =====
    if ($request->hasFile('laporan_TA')) {
        $request->validate([
            'laporan_TA' => 'required|mimes:pdf|max:20480',
        ]);
        $sidang->laporan_TA = 'storage/' . $request->file('laporan_TA')->store('uploads/laporan_ta', 'public');
        $sidang->status_draft_dosen1 = 'Menunggu';
        $sidang->status_draft_dosen2 = 'Menunggu';
        $sidang->catatan_draft_dosen1 = null;
        $sidang->catatan_draft_dosen2 = null;
    }

    if ($request->hasFile('from_persetujuan_sidang')) {
        $request->validate([
            'from_persetujuan_sidang' => 'required|mimes:pdf|max:20480',
        ]);
        $sidang->from_persetujuan_sidang = 'storage/' . $request->file('from_persetujuan_sidang')->store('uploads/form_persetujuan_sidang', 'public');
    }

    if ($request->hasFile('lembar_konsultasi')) {
        $request->validate([
            'lembar_konsultasi' => 'required|mimes:pdf|max:20480',
        ]);
        $sidang->lembar_konsultasi = 'storage/' . $request->file('lembar_konsultasi')->store('uploads/lembar_konsultasi', 'public');
    }

    $sidang->save();

    return redirect()->route('mahasiswa.sidang.draft')->with('success', 'File berhasil diupload.');
}
public function updateStatusDraft(Request $request, $id_sidang)
{
    $request->validate([
        'status_draft' => 'required|in:Revisi,Disetujui',
        'catatan' => 'nullable|string',
    ]);

    $user = Auth::user();
    $sidang = Sidang::findOrFail($id_sidang);

    if ($sidang->id_dosen1 == $user->id) {
        $sidang->status_draft_dosen1 = $request->status_draft;
        $sidang->catatan_draft_dosen1 = $request->catatan;
    } elseif ($sidang->id_dosen2 == $user->id) {
        $sidang->status_draft_dosen2 = $request->status_draft;
        $sidang->catatan_draft_dosen2 = $request->catatan;
    } else {
        return back()->with('error', 'Anda bukan dosen pembimbing sidang ini.');
    }

    $sidang->save();

    return back()->with('success', 'Status draft berhasil diperbarui.');
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
        'revisi_laporan' => 'required|mimes:pdf|max:20480', // hanya PDF max 20MB
    ]);

    $mahasiswa = Auth::user()->mahasiswa;
    $sidang = Sidang::where('id_kelompok', $mahasiswa->id_kelompok)->firstOrFail();

    // Simpan file revisi
    $filePath = $request->file('revisi_laporan')->store('uploads/revisi_laporan', 'public');

    // Cek jadwal sidang berdasarkan id_sempro
    $jadwal = Jadwal::where('id_ajuan', $sidang->id_sempro)
        ->where('jenis_acara', 'sidang')
        ->first();

    if ($jadwal) {
        $sidang->penguji_1_id = $jadwal->penguji_1_id;
        $sidang->penguji_2_id = $jadwal->penguji_2_id;
        $sidang->penguji_3_id = $jadwal->penguji_3_id;
    }

    // Update data sidang
    $sidang->revisi_laporan = 'storage/' . $filePath;
    $sidang->status_revisi_penguji_1 = 'Menunggu';
    $sidang->status_revisi_penguji_2 = 'Menunggu';
    $sidang->status_revisi_penguji_3 = 'Menunggu';
    $sidang->catatan_penguji_1 = null;
    $sidang->catatan_penguji_2 = null;
    $sidang->catatan_penguji_3 = null;
    $sidang->save();

    return redirect()->route('mahasiswa.sidang.revisi')->with('success', 'Revisi laporan berhasil diunggah dan telah dikirim ke dosen penguji.');
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
            'link_drive_proyek' => 'required|url|max:255',
        ]);

        $mahasiswa = Auth::user()->mahasiswa;
        $sidang = Sidang::where('id_kelompok', $mahasiswa->id_kelompok)->firstOrFail();

        // Validasi tambahan
        $errors = [];

        if (is_null($sidang->revisi_laporan)) {
            $errors['revisi_laporan'] = 'File revisi laporan belum tersedia.';
        }

        if (in_array($sidang->status_revisi_penguji_1, ['Menunggu', 'Revisi'])) {
            $errors['status_revisi_penguji_1'] = 'Status revisi dari Penguji 1 belum selesai.';
        }

        if (in_array($sidang->status_revisi_penguji_2, ['Menunggu', 'Revisi'])) {
            $errors['status_revisi_penguji_2'] = 'Status revisi dari Penguji 2 belum selesai.';
        }

        if (in_array($sidang->status_revisi_penguji_3, ['Menunggu', 'Revisi'])) {
            $errors['status_revisi_penguji_3'] = 'Status revisi dari Penguji 3 belum selesai.';
        }

        $adaJadwal = Jadwal::where('id_ajuan', $sidang->id_sempro)->exists();
        if (!$adaJadwal) {
            $errors['jadwal'] = 'Jadwal sidang belum tersedia untuk mahasiswa ini.';
        }

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

        $filePaths['link_drive_proyek'] = $request->input('link_drive_proyek');

        $sidang->update($filePaths);

        return redirect()->route('mahasiswa.sidang.final')->with('success', 'Laporan Final berhasil diupload.');

    } catch (ValidationException $e) {
        return back()->withErrors($e->validator)->withInput();

    } catch (ModelNotFoundException $e) {
        return back()->withErrors(['error' => 'Pra sidang belum selesai'])->withInput();

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
        'link_drive_proyek',
    ];

    if (!in_array($jenis, $allowedFields)) {
        return redirect()->back()->with('error', 'Jenis file tidak dikenali.');
    }

    $mahasiswa = Auth::user()->mahasiswa;
    $sidang = Sidang::where('id_kelompok', $mahasiswa->id_kelompok)->firstOrFail();

    if ($jenis === 'link_drive_proyek') {
        $sidang->$jenis = null;
    } else {
        $filePath = $sidang->$jenis;
        if ($filePath && \Storage::disk('public')->exists(str_replace('storage/', '', $filePath))) {
            \Storage::disk('public')->delete(str_replace('storage/', '', $filePath));
        }
        $sidang->$jenis = null;
    }

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
        'link_drive_proyek',
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
        'link_drive_proyek' => '',
    ];

    if (!array_key_exists($jenis, $allowedFields)) {
        abort(404);
    }

    if ($jenis == 'link_drive_proyek') {
        $request->validate([
            'file' => 'required|url|max:255',
        ]);
    } else {
        $request->validate([
            'file' => 'required|mimes:' . $allowedFields[$jenis] . '|max:20480',
        ]);
    }

    $mahasiswa = Auth::user()->mahasiswa;
    $sidang = Sidang::where('id_kelompok', $mahasiswa->id_kelompok)->firstOrFail();

    if ($jenis == 'link_drive_proyek') {
        $sidang->$jenis = $request->file;
    } else {
        $file = $request->file('file');
        $path = $file->storeAs("uploads/$jenis", $file->getClientOriginalName(), 'public');
        $sidang->$jenis = 'storage/' . $path;
    }

    $sidang->save();

    return redirect()->route('mahasiswa.sidang.final')->with('success', 'File berhasil diupdate.');
}


}
