<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Log::info('Seeding roles...');

        Role::firstOrCreate([
            'name' => 'dosen',
            'guard_name' => 'web'
        ]);
        Log::info('Role "dosen" created/created');

        Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web'
        ]);
        Log::info('Role "admin" created/created');

        Role::firstOrCreate([
            'name' => 'mahasiswa',
            'guard_name' => 'web'
        ]);
        Log::info('Role "mahasiswa" created/created');

        Role::firstOrCreate([
            'name' => 'panitia',
            'guard_name' => 'web'
        ]);
        Log::info('Role "panitia" created/created');
    }
}
