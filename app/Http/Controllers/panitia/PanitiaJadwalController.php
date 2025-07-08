<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Jadwal;
use App\Models\User;
use App\Models\PengajuanPembimbing;
use App\Models\Sempro;
use App\Exports\JadwalTemplateExport;
use App\Imports\JadwalTemplateImport;
use App\Models\Sidang;

class PanitiaJadwalController extends Controller
{
    public function index()
    {
        return redirect()->route ('panitia.jadwal.jenis.index', ['jenis' => 'seminar']);
    }

public function indexJenis($jenis)
{
    if (!in_array($jenis, ['seminar', 'sidang'])) abort(404);

    $idProdiPanitia = auth()->user()->id_prodi;

    // --- SEMINAR ---
    if ($jenis === 'seminar') {
        $approvedAjuanIds = Sempro::where('status_proposal_ta_dospem1', 'Disetujui')
            ->where('status_proposal_ta_dospem2', 'Disetujui')
            ->pluck('id_ajuan');

        $pengajuans = PengajuanPembimbing::with(['kelompok.anggota', 'dosen1', 'dosen2'])
            ->whereIn('id_ajuan', $approvedAjuanIds)
            ->whereNotNull('id_dosen2')
            ->whereHas('kelompok.anggota', function ($q) use ($idProdiPanitia) {
                $q->where('id_prodi', $idProdiPanitia);
            })
            ->get();
    }

    // --- SIDANG ---
    if ($jenis === 'sidang') {
        $pengajuans = PengajuanPembimbing::with(['kelompok.anggota', 'dosen1', 'dosen2'])
            ->whereHas('jadwals', function ($q) {
                $q->where('jenis_acara', 'seminar');
            })
            ->whereHas('sidang', function ($q) {
                $q->where('status_draft_dosen1', 'Disetujui')
                    ->where('status_draft_dosen2', 'Disetujui');
            })
            ->whereNotNull('id_dosen2')
            ->whereHas('kelompok.anggota', function ($q) use ($idProdiPanitia) {
                $q->where('id_prodi', $idProdiPanitia);
            })
            ->get();
    }

    // --- Jadwal yang sudah dibuat ---
    $jadwals = Jadwal::with([
            'pengajuan.kelompok.anggota.prodi',
            'pengajuan.dosen1',
            'pengajuan.dosen2',
            'penguji1',
            'penguji2',
            'penguji3',
        ])
        ->where('jenis_acara', $jenis)
        ->whereHas('pengajuan.kelompok.anggota', function ($q) use ($idProdiPanitia) {
            $q->where('id_prodi', $idProdiPanitia);
        })
        ->orderBy('tanggal', 'desc')
        ->orderBy('jam_mulai', 'asc')
        ->get();

    // --- Pengajuan yang belum dijadwalkan ---
    $pengajuanBelumTerjadwal = $pengajuans->filter(function ($pengajuan) use ($jadwals) {
        return !$jadwals->contains('id_ajuan', $pengajuan->id_ajuan);
    });

    return view("pages.panitia.jadwal.$jenis.index", compact('jadwals', 'pengajuanBelumTerjadwal', 'jenis'));
}


public function create($jenis)
{
    if (!in_array($jenis, ['seminar', 'sidang'])) abort(404);

    // Ambil dosen penguji
    $users = User::where('role_id', 3)->get(); // anggap role_id 3 = dosen

    // Pengajuan disiapkan khusus per jenis acara
    if ($jenis == 'seminar') {
        // Untuk SEMINAR
        $pengajuans = PengajuanPembimbing::whereHas('sempro', function ($q) {
                $q->where('status_proposal_ta_dospem1', 'Disetujui')
                  ->where('status_proposal_ta_dospem2', 'Disetujui');
            })
            ->whereDoesntHave('jadwals', function ($q) {
                $q->where('jenis_acara', 'seminar');
            })
            ->with(['kelompok.anggota.prodi', 'dosen1', 'dosen2'])
            ->get();

    } elseif ($jenis == 'sidang') {
        // Untuk SIDANG
        $pengajuans = PengajuanPembimbing::whereHas('jadwals', function ($q) {
                $q->where('jenis_acara', 'seminar');
            })
            ->whereHas('sidang', function ($q) {
                $q->where('status_draft_dosen1', 'Disetujui')
                  ->where('status_draft_dosen2', 'Disetujui');
            })
            ->whereDoesntHave('jadwals', function ($q) {
                $q->where('jenis_acara', 'sidang');
            })
            ->with(['kelompok.anggota.prodi', 'dosen1', 'dosen2'])
            ->get();
    }

    return view('pages.panitia.jadwal.'.$jenis.'.create', compact('jenis', 'pengajuans', 'users'));
}


    public function store(Request $request)
{
    $jenis = $request->input('jenis_acara');
    if (!in_array($jenis, ['seminar', 'sidang'])) abort(404);

    $validated = $request->validate([
        'jenis_acara'   => 'required|in:seminar,sidang',
        'tanggal'       => 'required|date',
        'jam_mulai'     => 'required|date_format:H:i',
        'jam_selesai'   => 'nullable|date_format:H:i|after:jam_mulai',
        'ruangan'       => 'required|string|max:255',
        'id_ajuan'      => 'required|exists:pengajuan_pembimbing,id_ajuan',
        'penguji_1_id'  => 'required|exists:users,id',
        'penguji_2_id'  => 'nullable|exists:users,id',
        'penguji_3_id'  => 'nullable|exists:users,id',
    ]);

    if ($jenis === 'seminar') {
        $isApproved = Sempro::where('id_ajuan', $validated['id_ajuan'])
            ->where('status_proposal_ta_dospem1', 'Disetujui')
            ->where('status_proposal_ta_dospem2', 'Disetujui')
            ->exists();

        if (!$isApproved) {
            return back()->with('error', 'Pengajuan belum memenuhi syarat SEMPRO.');
        }
    }

    if ($jenis === 'sidang') {

    // Ambil id_sempro berdasarkan id_ajuan
    $sempro = Sempro::where('id_ajuan', $validated['id_ajuan'])->first();

    if (!$sempro) {
        return back()->with('error', 'Data sempro tidak ditemukan.');
    }

    $isApprovedSidang = Sidang::where('id_sempro', $sempro->id_sempro)
        ->where('status_draft_dosen1', 'Disetujui')
        ->where('status_draft_dosen2', 'Disetujui')
        ->exists();

    if (!$isApprovedSidang) {
        return back()->with('error', 'Pengajuan belum memenuhi syarat SIDANG.');
    }

        // Cek apakah sudah seminar
        $isAlreadySeminar = Jadwal::where('id_ajuan', $validated['id_ajuan'])
            ->where('jenis_acara', 'seminar')
            ->exists();

        if (!$isAlreadySeminar) {
            return back()->with('error', 'Mahasiswa belum mengikuti SEMINAR, tidak bisa input SIDANG.');
        }
    }

    $pengajuan = PengajuanPembimbing::with(['kelompok.anggota.prodi', 'dosen1', 'dosen2'])->findOrFail($validated['id_ajuan']);
    $anggota = $pengajuan->kelompok->anggota;
    $firstAnggota = $anggota->first();

    Jadwal::create([
        'jenis_acara'    => $validated['jenis_acara'],
        'tanggal'        => $validated['tanggal'],
        'jam_mulai'      => $validated['jam_mulai'],
        'jam_selesai'    => $validated['jam_selesai'],
        'ruangan'        => $validated['ruangan'],
        'id_kelompok'    => $firstAnggota->id_kelompok,
        'id_ajuan'       => $validated['id_ajuan'],
        'nim'            => $anggota->pluck('nim_mhs')->join(', '),
        'nama'           => $anggota->pluck('nama_mhs')->join(', '),
        'prodi'          => $firstAnggota->prodi->nama_prodi ?? '-',
        'judul_ta'       => $pengajuan->judul_ta,
        'pembimbing_1'   => $pengajuan->dosen1->name ?? '-',
        'pembimbing_2'   => $pengajuan->dosen2->name ?? '-',
        'penguji_1_id'   => $validated['penguji_1_id'],
        'penguji_2_id'   => $validated['penguji_2_id'] ?? null,
        'penguji_3_id'   => $validated['penguji_3_id'] ?? null,
    ]);

    return redirect()->route('panitia.jadwal.jenis.index', ['jenis' => $validated['jenis_acara']])->with('success', 'Jadwal berhasil disimpan.');
}



    public function edit($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jenis = $jadwal->jenis_acara;

        if ($jenis === 'yudisium') {
            return view('pages.panitia.jadwal.yudisium.edit', compact('jadwal'));
        }

        $users = User::whereHas('role', fn($q) => $q->where('name', 'dosen'))->get();
        return view("pages.panitia.jadwal.$jenis.edit", compact('jadwal', 'users', 'jenis'));
    }

    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jenis = $jadwal->jenis_acara;

        $validated = $request->validate([
            'tanggal'     => 'required|date',
            'jam_mulai'   => 'required|date_format:H:i',
            'jam_selesai' => 'nullable|date_format:H:i|after:jam_mulai',
            'ruangan'     => 'required|string|max:255',
        ]);

        if ($jenis !== 'yudisium') {
            $penguji = $request->validate([
                'penguji_1_id' => 'required|exists:users,id',
                'penguji_2_id' => 'nullable|exists:users,id',
                'penguji_3_id' => 'nullable|exists:users,id',
            ]);
            $validated = array_merge($validated, $penguji);
        }

        $jadwal->update($validated);
        return redirect()->route('panitia.jadwal.jenis.index', ['jenis' => $jenis])->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jenis = $jadwal->jenis_acara;
        $jadwal->delete();

        return redirect()->route('panitia.jadwal.jenis.index', ['jenis' => $jenis])->with('success', 'Jadwal berhasil dihapus.');
    }

    // === BAGIAN EXPORT / IMPORT HANYA UNTUK SEMINAR & SIDANG ===

    public function export($jenis)
    {
        if (!in_array($jenis, ['seminar', 'sidang'])) abort(404);
        return Excel::download(new JadwalTemplateExport($jenis), "template-jadwal-{$jenis}.xlsx");
    }

    public function importForm($jenis)
    {
        if (!in_array($jenis, ['seminar', 'sidang'])) abort(404);
        return view('pages.panitia.jadwal.import', compact('jenis'));
    }

    public function importJadwal(Request $request)
{
    $jenis = $request->input('jenis');
    if (!in_array($jenis, ['seminar', 'sidang'])) abort(404);

    $request->validate([
        'file' => 'required|file|mimes:xlsx,xls',
    ]);

    try {
        Excel::import(new JadwalTemplateImport($jenis), $request->file('file'));

        $msg = 'Jadwal berhasil diimpor.';
        if (session()->has('import_warnings')) {
            $msg .= ' Beberapa baris dilewati:';
        }

        return redirect()->route('panitia.jadwal.jenis.index', ['jenis' => $jenis])
            ->with('success', $msg)
            ->with('warnings', session()->get('import_warnings'));
    } catch (\Exception $e) {
        return back()->with('error', 'Gagal impor: ' . $e->getMessage());
    }
}


    // === YUDISIUM TERPISAH ===

    public function indexYudisium()
    {
        $jadwals = Jadwal::where('jenis_acara', 'yudisium')
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_mulai')
            ->get();

        return view('pages.panitia.jadwal.yudisium.index', compact('jadwals'));
    }

    public function storeYudisium(Request $request)
    {
        $validated = $request->validate([
            'tanggal'     => 'required|date',
            'jam_mulai'   => 'required|date_format:H:i',
            'jam_selesai' => 'nullable|date_format:H:i|after:jam_mulai',
            'ruangan'     => 'required|string|max:255',
        ]);

        Jadwal::create(array_merge($validated, [
            'jenis_acara' => 'yudisium',
            'judul_ta' => '-', 'nim' => '-', 'nama' => '-', 'prodi' => '-',
            'pembimbing_1' => '-', 'pembimbing_2' => '-',
        ]));

        return redirect()->route('panitia.jadwal.yudisium.index')->with('success', 'Jadwal Yudisium berhasil disimpan.');
    }

        public function editYudisium($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        return view('pages.panitia.jadwal.yudisium.edit', compact('jadwal'));
    }

    public function updateYudisium(Request $request, $id)
    {
        $validated = $request->validate([
            'tanggal'     => 'required|date',
            'jam_mulai'   => 'required|date_format:H:i',
            'jam_selesai' => 'nullable|date_format:H:i|after:jam_mulai',
            'ruangan'     => 'required|string|max:255',
        ]);

        Jadwal::findOrFail($id)->update($validated);
        return redirect()->route('panitia.jadwal.yudisium.index')->with('success', 'Jadwal Yudisium berhasil diperbarui.');
    }

    public function destroyYudisium($id)
    {
        Jadwal::findOrFail($id)->delete();
        return redirect()->route('panitia.jadwal.yudisium.index')->with('success', 'Jadwal Yudisium berhasil dihapus.');
    }
}
