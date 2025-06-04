<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\Sidang;

class PanitiaSidangController extends Controller
{
    public function draft()
    {
        $sidangList = Sidang::with('mahasiswa.user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.panitia.sidang.draft', compact('sidangList'));
    }
  public function revisi()
    {
        $sidangList = Sidang::with('mahasiswa.user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.panitia.sidang.revisi', compact('sidangList'));
    }

    public function final()
    {
        $sidangList = Sidang::with('mahasiswa.user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.panitia.sidang.final', compact('sidangList'));
    }
}
