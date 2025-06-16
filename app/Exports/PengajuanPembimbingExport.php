<?php

namespace App\Exports;

use App\Models\PengajuanPembimbing;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PengajuanPembimbingExport implements FromCollection, WithHeadings
{
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function collection()
    {
        $pengajuanList = PengajuanPembimbing::with(['dosen1.dosen', 'dosen2.dosen', 'kelompok.anggota'])
            ->where('status', 'Diterima')
            ->whereHas('kelompok.anggota1.mahasiswa', function ($q) {
                if ($this->user->role->name === 'panitia' && $this->user->id_prodi !== null) {
                    $q->where('id_prodi', $this->user->id_prodi);
                }
            })->get();

        $data = [];

        foreach ($pengajuanList as $p) {
            $anggota = $p->kelompok?->anggota->map(function ($mhs) {
                return "{$mhs->nama_mhs} ({$mhs->nim_mhs})";
            })->implode(', ');

            $data[] = [
                'Judul TA' => $p->judul_ta,
                'Anggota Kelompok' => $anggota,
                'Dosen Pembimbing 1' => $p->dosen1->dosen->nama_dosen ?? '-',
                'Dosen Pembimbing 2' => $p->dosen2->dosen->nama_dosen ?? 'Belum Ditentukan',
            ];
        }

        return collect($data);
    }

    public function headings(): array
    {
        return ['Judul TA', 'Anggota Kelompok', 'Dosen Pembimbing 1', 'Dosen Pembimbing 2'];
    }
}
