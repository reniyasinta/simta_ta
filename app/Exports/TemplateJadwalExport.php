<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TemplateJadwalExport implements FromArray, WithHeadings
{
        public function array(): array
    {
        return [
            // Your data rows here, example empty rows for template
            ['','', '', '', '', ''],
        ];
    }

    public function headings(): array
    {
        return [
            'Judul TA',
            'Nama Mahasiswa',
            'Program Studi',
            'Kelas',
            'Jenis Acara',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Tempat',
            'Pembimbing 1',
            'Pembimbing 2',
            'Penguji 1',
            'Penguji 2',
            'Penguji 3',
        ];
    }
}
