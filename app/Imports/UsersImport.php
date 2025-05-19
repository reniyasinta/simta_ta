<?php

namespace App\Imports;
use App\Models\Role;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Prodi;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
public function model(array $row)
    {
        // Pastikan kolom role dan prodi tersedia
        if (!isset($row['role']) || !isset($row['prodi'])) {
            return null;
        }

        $roleName = strtolower(trim($row['role']));
        $prodiName = strtolower(trim($row['prodi']));

        $role = Role::whereRaw('LOWER(name) = ?', [$roleName])->first();
        $prodi = Prodi::whereRaw('LOWER(nama_prodi) = ?', [$prodiName])->first();

        if (!$role) return null;

        $nim_nip = $row['nim_nip'] ?? null;

        // Buat user
        $user = User::create([
            'name' => $row['name'],
            'email' => $row['email'],
            'password' => Hash::make($row['password']),
            'role_id' => $role->id,
            'nim' => $roleName === 'mahasiswa' ? $nim_nip : null,
            'nip' => $roleName === 'dosen' ? $nim_nip : null,
            'id_prodi' => $prodi?->id, // pakai null safe jika tidak ditemukan
        ]);
if ($roleName === 'mahasiswa' && $nim_nip) {
            Mahasiswa::create([
                'user_id' => $user->id,
                'nim_mhs' => $nim_nip,
                'nama_mhs' => $user->name,
                'id_prodi' => $prodi?->id,
                'prodi_mhs' => $prodi?->nama_prodi ?? null,
            ]);
        }

        if ($roleName === 'dosen' && $nim_nip) {
            Dosen::create([
                'user_id' => $user->id,
                'nip_dosen' => $nim_nip,
                'nama_dosen' => $user->name,
                'id_prodi' => $prodi?->id,
                'topik' => $row['topik'] ?? null,
                'no_telp' => $row['no_telp'] ?? null,
            ]);
        }

        return $user;
    }
}
