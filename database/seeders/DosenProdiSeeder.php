<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dosen;
use App\Models\Prodi;

class DosenProdiSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil dosen contoh
        $dosen = Dosen::where('nama_dosen', 'Dosen')->first(); // atau ganti sesuai dosen yang ada

        if (!$dosen) {
            $this->command->warn('Dosen tidak ditemukan. Seeder dilewati.');
            return;
        }

        // Ambil prodi Teknik Informatika & SIKC
        $prodis = Prodi::whereIn('nama_prodi', [
            'Teknik Informatika',
            'Sistem Informasi Kota Cerdas'
        ])->get();

        // Hubungkan ke pivot dosen_prodi
        $dosen->prodis()->sync($prodis->pluck('id'));

        $this->command->info('Seeder dosen_prodi selesai.');
    }
}
