<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Prodi;

class Berkas extends Model
{
    protected $table = 'berkas';
    protected $primaryKey = 'id_berkas';

    protected $fillable = [
        'nama_berkas',
        'file_path',
        'id_prodi',
    ];

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'id_prodi', 'id'); // ✅ relasi ke kolom 'id'
    }


}
