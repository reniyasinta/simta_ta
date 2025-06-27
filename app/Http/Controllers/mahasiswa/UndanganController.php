<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Undangan;
use App\Models\Jadwal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UndanganController extends Controller
{
    public function index(Request $request)
    {
        $jenis = $request->get('jenis', 'seminar');
        $idKelompok = Auth::user()->mahasiswa->id_kelompok ?? null;

        if (!$idKelompok) {
            return back()->with('error', 'Anda belum tergabung dalam kelompok.');
        }

        $jadwals = Jadwal::where('jenis_acara', $jenis)
            ->where('id_kelompok', $idKelompok)
            ->with(['penguji1', 'penguji2', 'penguji3'])
            ->get();

        return view('pages.mahasiswa.undangan.index', compact('jadwals', 'jenis'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'jenis_acara' => 'required|in:seminar,sidang',
            'penguji_id' => 'required|exists:users,id',
        ]);

        $jadwal = Jadwal::findOrFail($request->jadwal_id);
        $pengujiId = $request->penguji_id;
        $jenisAcara = $request->jenis_acara;

        return view('pages.mahasiswa.undangan.create', compact('jadwal', 'pengujiId', 'jenisAcara'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'penguji_id' => 'required|exists:users,id',
            'jenis_acara' => 'required|in:seminar,sidang',
            'undangan' => 'required|file|mimes:pdf|max:20480',
        ]);

        $folder = 'undangan/' . $validated['jenis_acara'];
        $filePath = $request->file('undangan')->store($folder, 'public');

        Undangan::updateOrCreate(
            [
                'jadwal_id' => $validated['jadwal_id'],
                'penguji_id' => $validated['penguji_id'],
                'jenis_acara' => $validated['jenis_acara'],
            ],
            [
                'id_kelompok' => Auth::user()->mahasiswa->id_kelompok,
                'file_path' => $filePath,
                'uploaded_by' => Auth::id(),
            ]
        );

        return redirect()->route('mahasiswa.undangan.index', ['jenis' => $validated['jenis_acara']])
            ->with('success', 'Undangan berhasil diunggah.');
    }
}
