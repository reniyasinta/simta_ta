<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil role sesuai nama
        $adminRole    = Role::where('name', 'admin')->first();
        $dosenRole    = Role::where('name', 'dosen')->first();
        $panitiaRole  = Role::where('name', 'panitia')->first();
        $mahasiswaRole = Role::where('name', 'mahasiswa')->first(); // <- fix typo

        // Admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('11'),
            'role_id' => $adminRole->id,
        ]);

        // Dosen user
        User::create([
            'name' => 'Dosen',
            'email' => 'dosen@gmail.com',
            'password' => Hash::make('22'),
            'role_id' => $dosenRole->id,
        ]);

        // Panitia user
        User::create([
            'name' => 'Panitia',
            'email' => 'panitia@gmail.com',
            'password' => Hash::make('33'),
            'role_id' => $panitiaRole->id,
        ]);

        // Mahasiswa user
        User::create([
            'name' => 'Mahasiswa',
            'email' => 'mahasiswa@gmail.com',
            'password' => Hash::make('44'),
            'role_id' => $mahasiswaRole->id,
        ]);
    }
}
