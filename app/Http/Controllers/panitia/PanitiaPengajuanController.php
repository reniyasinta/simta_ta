<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanPembimbing;
use App\Models\Dosen;
use App\Models\User;
use App\Exports\PengajuanPembimbingExport;
use Maatwebsite\Excel\Facades\Excel;


class PanitiaPengajuanController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Ambil pengajuan mahasiswa sesuai prodi panitia
        $pengajuanList = PengajuanPembimbing::with(['dosen1', 'dosen2', 'kelompok.anggota1.mahasiswa'])
            ->where('status', 'Diterima')
            ->whereHas('kelompok.anggota1.mahasiswa', function ($q) use ($user) {
                if ($user->role->name === 'panitia' && $user->id_prodi !== null) {
                    // Filter mahasiswa sesuai id_prodi panitia
                    $q->where('id_prodi', $user->id_prodi);
                }
                // Kalau panitia jurusan (id_prodi null) → tidak ada filter, tampilkan semua
            })
            ->get();

        // Dosen sementara → ambil semua dulu (nanti bisa pakai group mapping)
        $dosenList = Dosen::all();

        return view('pages.panitia.pengajuan.index', compact('pengajuanList', 'dosenList'));
    }

public function edit($id)
{
    $pengajuan = PengajuanPembimbing::with(['kelompok.anggota', 'dosen1.dosen', 'dosen2.dosen'])->findOrFail($id);

    $user = auth()->user(); // panitia login
    $idProdi = $user->id_prodi;

    // Mapping grup prodi
    $groupMapping = [
        [1, 2], // TI & SIKC
        [3, 4], // Listrik & TRPE
        [5, 6], // Elka & TRO
    ];

    // Default: hanya prodi sendiri
    $selectedGroup = [$idProdi];

    foreach ($groupMapping as $group) {
        if (in_array($idProdi, $group)) {
            $selectedGroup = $group;
            break;
        }
    }

    // Ambil dosen role_id = 3 dan exclude dosen1
    $dosenQuery = User::where('role_id', 3)
        ->where('id', '!=', $pengajuan->id_dosen1);

    if ($idProdi !== null) {
        $dosenQuery->whereIn('id_prodi', $selectedGroup);
    }

    $dosenList = $dosenQuery->get();

    // Hitung kuota dosen2
    foreach ($dosenList as $dosen) {
        $jumlahSebagai2 = PengajuanPembimbing::where('id_dosen2', $dosen->id)
            ->where('status', 'Diterima')
            ->with('kelompok')
            ->get()
            ->sum(function ($pengajuan) {
                return $pengajuan->kelompok?->anggota->count() ?? 0;
            });

        $dosenModel = \App\Models\Dosen::where('user_id', $dosen->id)->first();

        $kuotaP2 = null;
        if ($dosenModel) {
            $kuotaModel = \App\Models\KuotaBimbinganDosen::where('id_dosen', $dosenModel->id_dosen)->first();
            $kuotaP2 = $kuotaModel ? $kuotaModel->kuota_p2 : 0;
        }

        $dosen->kuota_total = $kuotaP2 ?? 0;
        $dosen->kuota_terpakai = $jumlahSebagai2;
    }

    return view('pages.panitia.pengajuan.edit', compact('pengajuan', 'dosenList'));
}


public function update(Request $request, $id)
{
    $request->validate([
        'id_dosen2' => 'required|exists:users,id', // karena select value = User.id
    ]);

    $pengajuan = PengajuanPembimbing::findOrFail($id);

    // Simpan langsung user_id ke id_dosen2
    $pengajuan->id_dosen2 = $request->id_dosen2;
    $pengajuan->save();

    return redirect()->route('panitia.pengajuan.index')->with('success', 'Dosen Pembimbing 2 berhasil ditetapkan.');
}
    public function export()
    {
        $user = auth()->user();
        return Excel::download(new PengajuanPembimbingExport($user), 'pengajuan_mahasiswa.xlsx');
    }
}
