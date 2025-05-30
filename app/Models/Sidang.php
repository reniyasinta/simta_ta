<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sidang extends Model
{
    use HasFactory;

    protected $table = 'sidang';
    protected $primaryKey = 'id_sidang';

    protected $fillable = [
        'id_mhs',
        'id_dosen',
        'id_sempro',
        'laporan_akhir',
        'lembar_konsultasi',
        'hasil_sidang',
        'status',
        'catatan_dosen',
    ];

    // Relasi ke mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mhs', 'id_mhs');
    }

    // Relasi ke dosen
    public function dosen()
    {
        return $this->belongsTo(User::class, 'id_dosen');
    }
}
