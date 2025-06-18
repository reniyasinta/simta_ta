<?php

namespace App\Imports;

use App\Models\Jadwal;
use App\Models\PengajuanPembimbing;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class JadwalTemplateImport implements ToCollection, WithHeadingRow
{
    protected $jenis;

    public function __construct($jenis)
    {
        $this->jenis = $jenis;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            // 🔧 Jika import untuk SIDANG, lakukan validasi eligibility
            if ($this->jenis == 'sidang') {

                $jadwal = Jadwal::find($row['id_jadwal']);

                if (!$jadwal) {
                    continue; // Jika data jadwal tidak ditemukan, skip
                }

                $pengajuan = PengajuanPembimbing::with(['jadwals', 'sidang'])
                    ->where('id_ajuan', $jadwal->id_ajuan)
                    ->first();

                // Validasi: Sudah dijadwalkan seminar
                $sudahSeminar = $pengajuan->jadwals->where('jenis_acara', 'seminar')->isNotEmpty();

                // Validasi: Status draft sidang disetujui
                $draftDisetujui = $pengajuan->sidang
                    && $pengajuan->sidang->status_draft_dosen1 == 'Disetujui'
                    && $pengajuan->sidang->status_draft_dosen2 == 'Disetujui';

                // Jika tidak memenuhi syarat, skip row
                if (!$sudahSeminar || !$draftDisetujui) {
                    continue;
                }
            }

            // Lakukan update jika lolos validasi
            if (!empty($row['id_jadwal'])) {
                Jadwal::where('id', $row['id_jadwal'])->update([
                    'tanggal' => $row['tanggal'],
                    'jam_mulai' => $row['jam_mulai'],
                    'ruangan' => $row['ruangan'],
                    'penguji_1_id' => $row['penguji_1'],
                    'penguji_2_id' => $row['penguji_2'],
                    'penguji_3_id' => $row['penguji_3'],
                ]);
            }
        }
    }
}
