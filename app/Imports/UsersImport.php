<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new User([
            'name' => $row['name'],
            'email' => $row['email'],
            'password' => Hash::make($row['password']),
            'role_id' => $row['role_id'] ?? 1,
            'nim' => ($row['role_id'] == 4) ? $row['nim_nip'] : null,
            'nip' => ($row['role_id'] == 3) ? $row['nim_nip'] : null,
        ]);
    }
}

