<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Prodi;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua role
        $adminRole     = Role::where('name', 'admin')->first();
        $panitiaRole   = Role::where('name', 'panitia')->first();
        $dosenRole     = Role::where('name', 'dosen')->first();
        $mahasiswaRole = Role::where('name', 'mahasiswa')->first();

        // Admin
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('11'),
                'role_id' => $adminRole->id,
                'nip' => 'ADM001',
                'id_prodi' => null,
            ]
        );

        // Panitia Jurusan (global)
        User::updateOrCreate(
            ['email' => 'panitia.jurusan@gmail.com'],
            [
                'name' => 'Panitia Jurusan',
                'password' => Hash::make('22'),
                'role_id' => $panitiaRole->id,
                'nip' => 'PAN000',
                'id_prodi' => null,
            ]
        );

        // Dosen contoh
        $defaultProdi = Prodi::first();

        User::updateOrCreate(
            ['email' => 'dosen@gmail.com'],
            [
                'name' => 'Dosen',
                'password' => Hash::make('33'),
                'role_id' => $dosenRole->id,
                'nip' => 'DOS001',
                'id_prodi' => $defaultProdi?->id,
            ]
        );

        // Mahasiswa contoh
        User::updateOrCreate(
            ['email' => 'mahasiswa@gmail.com'],
            [
                'name' => 'Mahasiswa',
                'password' => Hash::make('44'),
                'role_id' => $mahasiswaRole->id,
                'nim' => '220001',
                'id_prodi' => $defaultProdi?->id,
            ]
        );

        // Panitia per prodi
        $prodis = Prodi::all();
        foreach ($prodis as $index => $prodi) {
            $email = 'panitia' . ($index + 1) . '@gmail.com';
            $nip = 'PAN' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);

            User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => 'Panitia ' . $prodi->nama_prodi,
                    'password' => Hash::make('22'),
                    'role_id' => $panitiaRole->id,
                    'nip' => $nip,
                    'id_prodi' => $prodi->id,
                ]
            );
        }
    }
}
