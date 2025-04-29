<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;


class UsersTableSeeder extends Seeder
{

    public function run(): void
    {
        // Admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'], // Kriteria pencarian
            [
                'id' => '1',
                'name' => 'Admin',
                'password' => bcrypt('11'),
            ]
        );
        $admin->assignRole('admin');

    // Dosen user
    $dosen = User::firstOrCreate(
        ['email' => 'dosen@gmail.com'], // Kriteria pencarian
        [
            'id' => '2',
            'name' => 'Dosen',
            'password' => bcrypt('22'),
        ]
    );
    $dosen->assignRole('dosen');

    $mahasiswa = User::firstOrCreate(
        ['email' => 'mahasiswa@gmail.com'], // Kriteria pencarian
        [
            'id' => '3',
            'name' => 'Mahasiswa',
            'password' => bcrypt('33'),
        ]
    );
    $mahasiswa->assignRole('mahasiswa');

    $panitia = User::firstOrCreate(
        ['email' => 'panitia@gmail.com'], // Kriteria pencarian
        [
            'id' => '4',
            'name' => 'Panitia',
            'password' => bcrypt('44'),
        ]
    );
    $panitia->assignRole('panitia');
}
}
