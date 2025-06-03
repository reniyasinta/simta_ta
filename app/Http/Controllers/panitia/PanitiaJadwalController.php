<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\JadwalSeminarImport;
use App\Models\Jadwal;
use App\Models\User;
use App\Models\PengajuanPembimbing;
use App\Models\Dosen;
use App\Models\Sidang;

class PanitiaJadwalController extends Controller
{
    public function index()
    {
        return redirect()->route('pages.panitia.jadwal.seminar.index');
    }

    // ======= VIEW PER JENIS ACARA =======

public function seminar()
{
    $jadwals = Jadwal::where('jenis_acara', 'seminar')
        ->orderBy('tanggal', 'desc')
        ->orderBy('jam_mulai')
        ->with([
            'pengajuan.kelompok.anggota.prodi',
            'pengajuan.dosen1',
            'pengajuan.dosen2',
            'penguji1',
            'penguji2',
            'penguji3',
        ])
        ->get();

    $pengajuans = PengajuanPembimbing::with(['kelompok.anggota.prodi', 'dosen1', 'dosen2'])
        ->get()
        ->filter(function ($item) {
            return $item->dosen2 !== null && $item->kelompok !== null;
        });

    return view('pages.panitia.jadwal.seminar.index', [
        'jadwals' => $jadwals,
        'pengajuans' => $pengajuans,
        'jenis' => 'seminar'
    ]);
}


public function sidang()
{
    $jadwals = Jadwal::where('jenis_acara', 'sidang')
        ->orderBy('tanggal', 'desc')
        ->orderBy('jam_mulai')
        ->with('pengajuan.dosen1', 'pengajuan.dosen2', 'pengajuan.mahasiswa')
        ->get();

    return view('pages.panitia.jadwal.sidang.index', [
        'jadwals' => $jadwals,
        'jenis' => 'sidang'
    ]);
}


public function yudisium()
{
    $jadwals = Jadwal::where('jenis_acara', 'yudisium')
        ->orderBy('tanggal', 'desc')
        ->orderBy('jam_mulai')
        ->with('pengajuan.dosen1', 'pengajuan.dosen2', 'pengajuan.mahasiswa')
        ->get();

    return view('pages.panitia.jadwal.yudisium.index', [
        'jadwals' => $jadwals,
        'jenis' => 'yudisium'
    ]);
}


    // ======= TAMBAH JADWAL =======
    public function create(Request $request)
    {
        $jenis = $request->get('jenis', 'seminar');

        if (!in_array($jenis, ['seminar', 'yudisium', 'sidang'])) {
            abort(404);
        }

        $users = User::whereHas('role', fn($q) => $q->where('name', 'dosen'))->get();

        $pengajuans = PengajuanPembimbing::with(['kelompok.anggota', 'dosen1', 'dosen2'])
            ->get()
            ->filter(function ($item) {
                return $item->dosen2 !== null && $item->kelompok !== null;
            });

        return view("pages.panitia.jadwal.$jenis.create", compact('users', 'pengajuans', 'jenis'));
    }

public function store(Request $request)
{
    $jenis = $request->input('jenis_acara');

    // === VALIDASI UNTUK YUDISIUM ===
    if ($jenis === 'yudisium') {
        $validated = $request->validate([
            'jenis_acara' => 'required|in:yudisium',
            'tanggal'     => 'required|date',
            'jam_mulai'   => 'required|date_format:H:i',
            'jam_selesai' => 'nullable|date_format:H:i|after:jam_mulai',
            'ruangan'     => 'required|string|max:255',
        ]);

        $validated += [
            'judul_ta'      => '-',
            'nim'           => '-',
            'nama'          => '-',
            'prodi'         => '-',
            'pembimbing_1'  => '-',
            'pembimbing_2'  => '-',
        ];

        Jadwal::create($validated);

        return redirect()->route('jadwal.yudisium.index')->with('success', 'Jadwal Yudisium berhasil disimpan.');
    }

    // === VALIDASI UNTUK SEMINAR DAN SIDANG ===
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

    $pengajuan = PengajuanPembimbing::with(['kelompok.anggota.prodi', 'dosen1', 'dosen2'])->findOrFail($validated['id_ajuan']);
    $anggota = $pengajuan->kelompok->anggota;
    $firstAnggota = $anggota->first();

    Jadwal::create([
        'jenis_acara'    => $validated['jenis_acara'],
        'tanggal'        => $validated['tanggal'],
        'jam_mulai'      => $validated['jam_mulai'],
        'jam_selesai'    => $validated['jam_selesai'],
        'ruangan'        => $validated['ruangan'],
        'id_mhs'         => $firstAnggota->id_mhs ?? null,
        'id_ajuan'       => $validated['id_ajuan'],
        'nim'            => $anggota->pluck('nim')->join(', '),
        'nama'           => $anggota->pluck('nama_mhs')->join(', '),
        'prodi'          => $firstAnggota->prodi->nama_prodi ?? '-',
        'judul_ta'       => $pengajuan->judul_ta,
        'pembimbing_1'   => $pengajuan->dosen1->name ?? '-',
        'pembimbing_2'   => $pengajuan->dosen2->name ?? '-',
        'penguji_1_id'   => $validated['penguji_1_id'],
        'penguji_2_id'   => $validated['penguji_2_id'] ?? null,
        'penguji_3_id'   => $validated['penguji_3_id'] ?? null,
    ]);

    return redirect()->route('jadwal.' . $validated['jenis_acara'] . '.index')->with('success', 'Jadwal berhasil disimpan.');
}


public function edit($id)
{
    $jadwal = Jadwal::findOrFail($id);
    $jenis = $jadwal->jenis_acara;

    // Ambil daftar dosen sebagai penguji
    $users = User::whereHas('role', fn($q) => $q->where('name', 'dosen'))->get();

    // Kirim data ke view sesuai jenis acara
    return view("pages.panitia.jadwal.$jenis.edit", compact('jadwal', 'users', 'jenis'));
}


public function update(Request $request, $id)
{
    $jadwal = Jadwal::findOrFail($id);

    // Validasi umum
    $validated = $request->validate([
        'tanggal'     => 'required|date',
        'jam_mulai'   => 'required|date_format:H:i',
        'jam_selesai' => 'nullable|date_format:H:i|after:jam_mulai',
        'ruangan'     => 'required|string|max:255',
    ]);

    if ($jadwal->jenis_acara === 'yudisium') {
        // Tambahan default agar sesuai saat update
        $validated += [
            'judul_ta'      => '-',
            'nim'           => '-',
            'nama'          => '-',
            'prodi'         => '-',
            'pembimbing_1'  => '-',
            'pembimbing_2'  => '-',
        ];
    } else {
        // Validasi penguji hanya untuk seminar/sidang
        $validatedPenguji = $request->validate([
            'penguji_1_id' => 'required|exists:users,id',
            'penguji_2_id' => 'nullable|exists:users,id',
            'penguji_3_id' => 'nullable|exists:users,id',
        ]);

        $validated = array_merge($validated, $validatedPenguji);
    }

    $jadwal->update($validated);

    return redirect()
        ->route('jadwal.' . $jadwal->jenis_acara . '.index')
        ->with('success', 'Jadwal berhasil diperbarui.');
}

    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();
        return redirect()->route('jadwal.yudisium.index')->with('success', 'Jadwal Yudisium berhasil dihapus.');
    }

    // ======= IMPORT JADWAL =======
    public function importForm(Request $request)
    {
        $jenis = $request->get('jenis', 'seminar');
        return view('pages.panitia.jadwal.import', compact('jenis'));
    }

    public function importView()
    {
        return view('pages.panitia.jadwal.import');
    }

    public function importJadwal(Request $request)
    {
        $jenis = $request->get('jenis', 'seminar');

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new JadwalSeminarImport, $request->file('file'));
            return redirect()->route('jadwal.' . $jenis . '.index')->with('success', 'Jadwal berhasil diimpor.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal impor: ' . $e->getMessage());
        }
    }
}
