<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Surat;

class PimpinanSuratController extends Controller
{
    public function index()
    {
        $suratList = Surat::with(['mahasiswa.kelompok.anggota'])->orderBy('created_at', 'desc')->get();
        return view('pages.pimpinan.surat.index', compact('suratList'));
    }
}
