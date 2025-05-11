<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Jadwal extends Model
{
    protected $table = 'jadwals';

    protected $fillable = [
        'tanggal_mulai',
        'tanggal_selesai',
        'tempat',
        'jenis_acara',
        'judul_ta',
        'nim',
        'nama',
        'prodi',
        'kelas',
        'id_mhs',
        'id_dosen',
        'pembimbing_1',
        'pembimbing_2',
        'penguji_1_id',
        'penguji_2_id',
        'penguji_3_id',
    ];

    /**
     * Relasi ke mahasiswa (pemilik TA)
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mhs', 'id_mhs');
    }

    /**
     * Relasi ke dosen pembimbing utama
     */
    public function dosen()
    {
        return $this->belongsTo(User::class, 'id_dosen');
    }

    /**
     * Relasi ke dosen penguji 1
     */
    public function penguji1()
    {
        return $this->belongsTo(User::class, 'penguji_1_id');
    }

    /**
     * Relasi ke dosen penguji 2
     */
    public function penguji2()
    {
        return $this->belongsTo(User::class, 'penguji_2_id');
    }

    /**
     * Relasi ke dosen penguji 3
     */
    public function penguji3()
    {
        return $this->belongsTo(User::class, 'penguji_3_id');
    }
}
