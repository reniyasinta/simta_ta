<?php

namespace App\Http\Controllers\Panitia;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PanitiaController extends Controller
{
    public function index()
    {
        return view('pages.panitia.dashboard');
    }
}
