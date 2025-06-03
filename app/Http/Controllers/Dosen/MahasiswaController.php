<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;

class MahasiswaController extends Controller
{
    public function show($id)
    {
        $mahasiswa = Mahasiswa::with(['user', 'prodi'])->findOrFail($id);

        return view('pages.dosen.mahasiswa.show', compact('mahasiswa'));
    }
}
