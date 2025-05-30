<?php
namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sidang;

class SidangController extends Controller
{
    public function index()
    {
        $sidangs = Sidang::with('mahasiswa')
            ->where('id_dosen', auth()->id())
            ->get();

        return view('pages.dosen.sidang.index', compact('sidangs'));
    }

    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Diterima,Revisi',
            'catatan_dosen' => 'nullable|string',
        ]);

        $sidang = Sidang::findOrFail($id);
        $sidang->status = $request->status;
        $sidang->catatan_dosen = $request->catatan_dosen;
        $sidang->save();

        return redirect()->back()->with('success', 'Status diperbarui');
    }
}
