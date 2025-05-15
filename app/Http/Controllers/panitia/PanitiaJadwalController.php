<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\JadwalSeminarImport;
use App\Models\Jadwal;
use App\Models\User;

class PanitiaJadwalController extends Controller
{
    public function index()
    {
        $jadwals = Jadwal::orderBy('tanggal_mulai', 'desc')->get();
        return view('pages.panitia.jadwal.index', compact('jadwals'));
    }

    public function create()
    {
        // Ambil semua user yang berperan sebagai penguji (dosen)
        $users = User::whereHas('role', function ($query) {
            $query->where('name', 'dosen'); // atau 'penguji' jika role tersebut adalah penguji
        })->get();

        return view('pages.panitia.jadwal.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_mulai'     => 'required|date',
            'tanggal_selesai'   => 'nullable|date|after_or_equal:tanggal_mulai',
            'tempat'            => 'required|string|max:255',
            'jenis_acara'       => 'required|in:sosialisasi,seminar_proposal,sidang_ta',
            'judul_ta'          => 'required|string|max:255',
            'nim'               => 'required|string|max:20',
            'nama'              => 'required|string|max:100',
            'prodi'             => 'required|string|max:100',
            'kelas'             => 'required|string|max:50',
            'pembimbing_1'      => 'required|string|max:100',
            'pembimbing_2'      => 'nullable|string|max:100',
            'penguji_1_id'      => 'required|exists:users,id',
            'penguji_2_id'      => 'nullable|exists:users,id',
            'penguji_3_id'      => 'nullable|exists:users,id',
        ]);

        Jadwal::create($validated);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil disimpan.');
    }

    public function importForm()
    {
        return view('pages.panitia.jadwal.import');
    }

    public function importView()
    {
        return view('pages.panitia.jadwal.import');
    }

    public function importJadwal(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new JadwalSeminarImport, $request->file('file'));
            return back()->with('success', 'Jadwal berhasil diimpor.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal impor: ' . $e->getMessage());
        }
    }
}
