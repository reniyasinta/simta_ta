<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;

class TemplateUserExport implements FromArray
{
    public function array(): array
    {
        return [
            ['name', 'email', 'password', 'role', 'nim_nip','kelas', 'prodi']
        ];
    }
}
