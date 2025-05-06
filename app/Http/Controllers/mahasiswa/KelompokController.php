<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelompok;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\DB;

class KelompokController extends Controller
{
    public function index()
    {
        $kelompok = Kelompok::with('mahasiswa')->get(); // memuat relasi anggota

        return view('pages.mahasiswa.kelompok.index', compact('kelompok'));
    }



    public function create()
    {
        return view('pages.mahasiswa.kelompok.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nims' => 'required|array|min:1|max:3',
            'nims.*' => 'required|string|distinct|exists:mahasiswa,nim_mhs',
        ]);

        $kelompok = Kelompok::create();

        $authMahasiswa = auth()->user()->mahasiswa;
        if ($authMahasiswa) {
            $authMahasiswa->id_kelompok = $kelompok->id_kelompok;
            $authMahasiswa->save();
        }

        foreach ($request->nims as $nim) {
            $mahasiswa = Mahasiswa::where('nim_mhs', $nim)->first();
            if ($mahasiswa) {
                $mahasiswa->id_kelompok = $kelompok->id_kelompok;
                $mahasiswa->save();
            }
        }

        return redirect()->route('kelompok.index')->with('success', 'Kelompok berhasil dibuat!');
    }
}

