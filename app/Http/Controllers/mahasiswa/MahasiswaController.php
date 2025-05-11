<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Jadwal;

class MahasiswaController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // pastikan user adalah instance dari App\Models\User
        $mahasiswa = $user->mahasiswa; // relasi dari model User ke Mahasiswa

        if (!$mahasiswa) {
            return redirect()->back()->withErrors(['Anda belum terdaftar sebagai mahasiswa.']);
        }

        $jadwals = Jadwal::where('id_mhs', $mahasiswa->id_mhs)->get();

        return view('pages.mahasiswa.dashboard', compact('jadwals'));
    }
}

