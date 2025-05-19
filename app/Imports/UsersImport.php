<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Buat user terlebih dahulu
        $user = User::create([
            'name' => $row['name'], // Kolom Excel: name
            'email' => $row['email'],
            'password' => Hash::make($row['password']),
            'role_id' => $row['role_id'] ?? 1,
            'nim' => ($row['role_id'] == 4) ? $row['nim_nip'] : null,
            'nip' => ($row['role_id'] == 3) ? $row['nim_nip'] : null,
            'prodi_id' => $row['id_prodi'] ?? null,
        ]);

        // Jika Mahasiswa
        if ($user->role_id == 4) {
            Mahasiswa::create([
                'user_id' => $user->id,
                'nim_mhs' => $user->nim,
                'nama_mhs' => $user->name,
                'id_prodi' => $row['id_prodi'] ?? null,
                'prodi_mhs' => $row['prodi_mhs'] ?? null,
                'semester' => $row['semester'] ?? null,
                // 'id_kelompok' bisa null (default)
            ]);
        }

        // Jika Dosen
        if ($user->role_id == 3) {
            Dosen::create([
                'user_id' => $user->id,
                'nip_dosen' => $user->nip,
                'nama_dosen' => $user->name,
                'id_prodi' => $row['id_prodi'] ?? null,
                'topik' => $row['topik'] ?? null,
                'no_telp' => $row['no_telp'] ?? null,
            ]);
        }

        return $user;
    }
}
