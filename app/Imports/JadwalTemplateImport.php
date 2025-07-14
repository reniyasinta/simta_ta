<?php

namespace App\Imports;

use App\Models\Jadwal;
use App\Models\PengajuanPembimbing;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class JadwalTemplateImport implements ToCollection, WithHeadingRow
{
    protected $jenis;
    protected $warnings = [];
    protected $processedCount = 0;

    public function __construct($jenis)
    {
        $this->jenis = $jenis;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            // Konversi Tanggal & Jam
            $tanggal = is_numeric($row['tanggal']) ? Date::excelToDateTimeObject($row['tanggal'])->format('Y-m-d') : $row['tanggal'];
            $jam_mulai = is_numeric($row['jam_mulai']) ? Date::excelToDateTimeObject($row['jam_mulai'])->format('H:i') : $row['jam_mulai'];
            $jam_selesai = is_numeric($row['jam_selesai']) ? Date::excelToDateTimeObject($row['jam_selesai'])->format('H:i') : $row['jam_selesai'];

            // Ambil ID Ajuan
            $idAjuan = $row['id_ajuan'];
            $pengajuan = PengajuanPembimbing::with(['jadwals', 'sidang'])->find($idAjuan);
            if (!$pengajuan) {
                $this->warnings[] = "Baris " . ($index + 2) . ": ID Ajuan tidak ditemukan.";
                continue;
            }

            // Validasi SIDANG
            if ($this->jenis === 'sidang') {
                $sudahSeminar = $pengajuan->jadwals->where('jenis_acara', 'seminar')->isNotEmpty();
                $draftDisetujui = $pengajuan->sidang
                    && $pengajuan->sidang->status_draft_dosen1 == 'Disetujui'
                    && $pengajuan->sidang->status_draft_dosen2 == 'Disetujui';

                if (!$sudahSeminar || !$draftDisetujui) {
                    $this->warnings[] = "Baris " . ($index + 2) . ": Belum memenuhi syarat SIDANG.";
                    continue;
                }
            }

            // Cari ID dosen dari nama
            $penguji1 = User::where('name', $row['penguji_1'])->first();
            $penguji2 = User::where('name', $row['penguji_2'])->first();
            $penguji3 = User::where('name', $row['penguji_3'])->first();

            // Wajib: Penguji 1 harus ada
            if (!$penguji1) {
                $this->warnings[] = "Baris " . ($index + 2) . ": Nama Penguji 1 '{$row['penguji_1']}' tidak ditemukan.";
                continue;
            }

            // Simpan jadwal
            Jadwal::updateOrCreate(
                ['id_ajuan' => $idAjuan, 'jenis_acara' => $this->jenis],
                [
                    'tanggal' => $tanggal,
                    'jam_mulai' => $jam_mulai,
                    'jam_selesai' => $jam_selesai,
                    'ruangan' => $row['ruangan'],
                    'penguji_1_id' => $penguji1->id,
                    'penguji_2_id' => $penguji2?->id,
                    'penguji_3_id' => $penguji3?->id,
                            // Tambahan agar tidak error
                    'judul_ta' => $pengajuan->judul_ta,
                    'nim' => $pengajuan->kelompok->anggota->pluck('nim_mhs')->join(', '),
                    'nama' => $pengajuan->kelompok->anggota->pluck('nama_mhs')->join(', '),
                    'prodi' => $pengajuan->kelompok->anggota->first()->prodi->nama_prodi ?? '-',
                    'pembimbing_1' => $pengajuan->dosen1->name ?? '-',
                    'pembimbing_2' => $pengajuan->dosen2->name ?? '-',
                ]
            );
             $this->processedCount++;
        }

        // Simpan warning ke session
        if (!empty($this->warnings)) {
            session()->flash('import_warnings', $this->warnings);
        }
    }
       public function getProcessedCount() // ✅ Tambahan
        {
            return $this->processedCount;
        }
}
