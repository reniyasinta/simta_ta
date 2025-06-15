<?php

namespace App\Exports;

use App\Models\Jadwal;
use App\Models\PengajuanPembimbing;
use App\Models\Sempro;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JadwalTemplateExport implements FromCollection, WithHeadings
{
    protected $jenis;

    public function __construct($jenis)
    {
        $this->jenis = $jenis;
    }

public function collection()
{
    $data = collect();

    // Mahasiswa yang sudah ada jadwal tapi belum lengkap
    $jadwalBelumLengkap = Jadwal::where('jenis_acara', $this->jenis)
        ->where(function ($query) {
            $query->whereNull('tanggal')
                ->orWhereNull('jam_mulai')
                ->orWhereNull('ruangan')
                ->orWhereNull('penguji_1_id');
        })
        ->with(['pengajuan.kelompok.anggota', 'pengajuan.dosen1', 'pengajuan.dosen2'])
        ->get();

    foreach ($jadwalBelumLengkap as $item) {
        $data->push([
            'ID Jadwal' => $item->id,
            'Nama Mahasiswa' => $item->pengajuan->kelompok->anggota->pluck('nama_mhs')->join(', '),
            'Judul TA' => $item->pengajuan->judul_ta,
            'Pembimbing 1' => $item->pengajuan->dosen1->name ?? '-',
            'Pembimbing 2' => $item->pengajuan->dosen2->name ?? '-',
            'Tanggal' => $item->tanggal,
            'Jam Mulai' => $item->jam_mulai,
            'Ruangan' => $item->ruangan,
            'Penguji 1' => $item->penguji_1_id,
            'Penguji 2' => $item->penguji_2_id,
            'Penguji 3' => $item->penguji_3_id,
        ]);
    }

    // Mahasiswa yang belum punya jadwal sama sekali
    if ($this->jenis == 'seminar') {
        $approvedAjuanIds = Sempro::where('status_laporan_ta_dospem1', 'Disetujui')
            ->where('status_laporan_ta_dospem2', 'Disetujui')
            ->pluck('id_ajuan');

        $pengajuansBelumAdaJadwal = PengajuanPembimbing::whereIn('id_ajuan', $approvedAjuanIds)
            ->whereNotIn('id_ajuan', Jadwal::where('jenis_acara', 'seminar')->pluck('id_ajuan'))
            ->with(['kelompok.anggota', 'dosen1', 'dosen2'])
            ->get();

        foreach ($pengajuansBelumAdaJadwal as $pengajuan) {
            $data->push([
                'Nama Mahasiswa' => $pengajuan->kelompok->anggota->pluck('nama_mhs')->join(', '),
                'Judul TA' => $pengajuan->judul_ta,
                'Pembimbing 1' => $pengajuan->dosen1->name ?? '-',
                'Pembimbing 2' => $pengajuan->dosen2->name ?? '-',
                'Tanggal' => '',
                'Jam Mulai' => '',
                'Ruangan' => '',
                'Penguji 1' => '',
                'Penguji 2' => '',
                'Penguji 3' => '',
            ]);
        }
    }

    // 🔧 Tambahkan logika SIDANG
    if ($this->jenis == 'sidang') {
        $pengajuansBelumAdaJadwal = PengajuanPembimbing::whereHas('jadwals', function ($q) {
                $q->where('jenis_acara', 'seminar');
            })
            ->whereHas('sidang', function ($q) {
                $q->where('status_draft_dosen1', 'Disetujui')
                  ->where('status_draft_dosen2', 'Disetujui');
            })
            ->whereNotIn('id_ajuan', Jadwal::where('jenis_acara', 'sidang')->pluck('id_ajuan'))
            ->with(['kelompok.anggota', 'dosen1', 'dosen2'])
            ->get();

        foreach ($pengajuansBelumAdaJadwal as $pengajuan) {
            $data->push([
                'Nama Mahasiswa' => $pengajuan->kelompok->anggota->pluck('nama_mhs')->join(', '),
                'Judul TA' => $pengajuan->judul_ta,
                'Pembimbing 1' => $pengajuan->dosen1->name ?? '-',
                'Pembimbing 2' => $pengajuan->dosen2->name ?? '-',
                'Tanggal' => '',
                'Jam Mulai' => '',
                'Ruangan' => '',
                'Penguji 1' => '',
                'Penguji 2' => '',
                'Penguji 3' => '',
            ]);
        }
    }

    return $data;
}

    public function headings(): array
    {
        return [
            'Nama Mahasiswa',
            'Judul TA',
            'Pembimbing 1',
            'Pembimbing 2',
            'Tanggal',
            'Jam Mulai',
            'Ruangan',
            'Penguji 1',
            'Penguji 2',
            'Penguji 3',
        ];
    }
}
