<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Jadwal;

class DosenController extends Controller
{
    public function index()
    {

    $dosenId = Auth::id();

    $jadwals = Jadwal::where('penguji_1_id', $dosenId)
              ->orWhere('penguji_2_id', $dosenId)
              ->orWhere('penguji_3_id', $dosenId)
              ->get();

        return view('pages.dosen.dashboard', compact('jadwals'));
    }
}
