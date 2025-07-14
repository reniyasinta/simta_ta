<?php


namespace App\Exports;

use App\Models\Jadwal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class JadwalSidangExport implements FromCollection, WithHeadings
{
   public function collection(): Collection
{
    $jadwals = Jadwal::with([
        'pengajuan.kelompok.anggota1.mahasiswa.prodi',
        'pengajuan.kelompok.anggota2.mahasiswa.prodi',
        'pengajuan.kelompok.anggota3.mahasiswa.prodi',
        'pengajuan.dosen1.dosen',
        'pengajuan.dosen2.dosen',
        'penguji1', 'penguji2', 'penguji3'
    ])
    ->where('jenis_acara', 'sidang')
    ->get();

    return collect($jadwals)->map(function ($item) {
        $kelompok = $item->pengajuan->kelompok;
        $namaMahasiswa = $kelompok->anggota->pluck('nama_mhs')->join(', ');
        $prodi = optional($kelompok->anggota->first()->prodi ?? null)->nama_prodi ?? '-';

        return [
            'Nama Mahasiswa' => $namaMahasiswa,
            'Judul TA'       => $item->pengajuan->judul_ta,
            'Pembimbing 1'   => optional($item->pengajuan->dosen1->dosen)->nama_dosen,
            'Pembimbing 2'   => optional($item->pengajuan->dosen2->dosen)->nama_dosen,
            'Tanggal'        => $item->tanggal,
            'Jam Mulai'      => $item->jam_mulai,
            'Jam Selesai'    => $item->jam_selesai,
            'Ruangan'        => $item->ruangan,
            'Penguji 1'      => optional($item->penguji1)->name,
            'Penguji 2'      => optional($item->penguji2)->name,
            'Penguji 3'      => optional($item->penguji3)->name,
        ];
    });
}

    public function headings(): array
    {
        return [
         'Nama Mahasiswa', 'Judul TA', 'Pembimbing 1', 'Pembimbing 2',
        'Tanggal', 'Jam Mulai', 'Jam Selesai', 'Ruangan',
        'Penguji 1', 'Penguji 2', 'Penguji 3',
        ];
    }
}
