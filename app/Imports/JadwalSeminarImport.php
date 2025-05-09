<?php

namespace App\Imports;

use App\Models\Jadwal;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
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

        $sudahAda = Jadwal::where('mahasiswa_id', $mahasiswa->id)
            ->where('jenis_acara', $row['jenis_acara'])
            ->exists();

        if ($sudahAda) {
            throw new \Exception("Mahasiswa '{$row['nama']}' sudah memiliki jadwal {$row['jenis_acara']}.");
        }

        return new Jadwal([
            'mahasiswa_id' => $mahasiswa->id,
            'judul_acara' => $row['judul_acara'],
            'jenis_acara' => $row['jenis_acara'],
            'tanggal_mulai' => $row['tanggal_mulai'],
            'tanggal_selesai' => $row['tanggal_selesai'],
            'tempat' => $row['tempat'],
            'deskripsi' => $row['deskripsi'],
        ]);
    }
}
