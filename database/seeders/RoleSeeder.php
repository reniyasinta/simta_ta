<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use App\Models\Role;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::updateOrcreate(['name' => 'admin']);
        Role::updateOrcreate(['name' => 'panitia']);
        Role::updateOrcreate(['name' => 'dosen']);
        Role::updateOrcreate(['name' => 'mahasiswa']);
        Role::updateOrCreate(['name' => 'pimpinan']);
    }
}
