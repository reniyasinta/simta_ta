<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    public function model(array $row)
    {
        return new User([
            'name' => $row[0],
            'email' => $row[1],
            'password' => Hash::make($row[2]), // pastikan file sudah hash atau plaintext
            'role_id' => isset($row[3]) ? $row[3] : 1,
            'nim' => ($row[3] == 4) ? $row[4] : null, // 4 = mahasiswa
            'nip' => ($row[3] == 3) ? $row[4] : null, // 3 = dosen
        ]);
    }
}

