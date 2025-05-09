<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jadwal;
use App\Models\User;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil user dosen yang sudah dibuat di UsersTableSeeder
        $penguji1 = User::where('name', 'Dosen')->first(); // asumsi 'Dosen' adalah penguji 1
        $penguji2 = User::where('name', 'Panitia')->first(); // contoh sebagai penguji 2
        $penguji3 = User::where('name', 'Admin')->first(); // contoh sebagai penguji 3

        Jadwal::create([
            'tanggal_mulai' => now()->addDays(3),
            'tanggal_selesai' => now()->addDays(3)->addHours(2),
            'tempat' => 'Ruang Seminar A',
            'jenis_acara' => 'seminar_proposal',
            'judul_ta' => 'Sistem Informasi Akademik',
            'nim' => '1234567890',
            'nama' => 'Ahmad Rudiansyah',
            'prodi' => 'Teknik Informatika',
            'kelas' => 'TI-4A',
            'pembimbing_1' => 'Dr. Siti Rahma',
            'pembimbing_2' => 'Dr. Budi Hartono',
            'penguji_1_id' => $penguji1->id ?? null,
            'penguji_2_id' => $penguji2->id ?? null,
            'penguji_3_id' => $penguji3->id ?? null,
        ]);
    }
}
