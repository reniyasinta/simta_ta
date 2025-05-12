<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Berkas;
use Illuminate\Support\Facades\Storage;



class BerkasController extends Controller
{
     public function index()
    {
        $berkas = Berkas::all(); // ambil semua berkas
        return view('pages.mahasiswa.berkas.index', compact('berkas'));
    }

    public function download($id)
    {
        $berkas = Berkas::findOrFail($id);

        if (Storage::disk('public')->exists($berkas->file_path)) {
            return Storage::disk('public')->download($berkas->file_path, $berkas->nama_berkas . '.pdf');
        }

        return redirect()->back()->with('error', 'File tidak ditemukan.');
    }

}
