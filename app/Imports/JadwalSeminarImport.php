<?php

namespace App\Imports;

use App\Models\Jadwal;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class JadwalSeminarImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $mahasiswa = Mahasiswa::where('nama', $row['nama'])->first();

        if (!$mahasiswa) {
            throw new \Exception("Mahasiswa '{$row['nama']}' tidak ditemukan.");
        }

        $bentrok = Jadwal::where('tempat', $row['tempat'])
            ->where(function ($query) use ($row) {
                $query->whereBetween('tanggal_mulai', [$row['tanggal_mulai'], $row['tanggal_selesai']])
                      ->orWhereBetween('tanggal_selesai', [$row['tanggal_mulai'], $row['tanggal_selesai']]);
            })->exists();

        if ($bentrok) {
            throw new \Exception("Jadwal bentrok di {$row['tempat']} tanggal {$row['tanggal_mulai']}.");
        }

        $sudahAda = Jadwal::where('id_mhs', $mahasiswa->id_mhs)
            ->where('jenis_acara', $row['jenis_acara'])
            ->exists();

        if ($sudahAda) {
            throw new \Exception("Mahasiswa '{$row['nama']}' sudah memiliki jadwal {$row['jenis_acara']}.");
        }

        return new Jadwal([
            'id_mhs'           => $mahasiswa->id_mhs,
            'nim'              => $mahasiswa->nim,
            'nama'             => $mahasiswa->nama,
            'prodi'            => $mahasiswa->prodi,
            'kelas'            => $mahasiswa->kelas,
            'judul_ta'         => $row['judul_ta'] ?? $mahasiswa->judul_ta, // fallback jika tidak ada di Excel
            'jenis_acara'      => $row['jenis_acara'],
            'tanggal_mulai'    => $row['tanggal_mulai'],
            'tanggal_selesai'  => $row['tanggal_selesai'],
            'tempat'           => $row['tempat'],
            'pembimbing_1'     => $mahasiswa->pembimbing_1,
            'pembimbing_2'     => $mahasiswa->pembimbing_2,
            // Tambahkan kolom penguji jika ada di Excel
            'penguji_1_id'     => $row['penguji_1_id'] ?? null,
            'penguji_2_id'     => $row['penguji_2_id'] ?? null,
            'penguji_3_id'     => $row['penguji_3_id'] ?? null,
        ]);
    }
}
